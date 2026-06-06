import sys
import json
import math
from collections import defaultdict

def mean_std(values):
    n = len(values)
    if n == 0:
        return 0.0, 0.0
    avg = sum(values) / n
    variance = sum((x - avg) ** 2 for x in values) / max(1, n - 1)
    return avg, math.sqrt(variance)

def cosine_similarity(v1, v2):
    dot = sum(v1.get(k, 0) * v2.get(k, 0) for k in set(v1) & set(v2))
    mag1 = math.sqrt(sum(x ** 2 for x in v1.values()))
    mag2 = math.sqrt(sum(x ** 2 for x in v2.values()))
    if mag1 == 0 or mag2 == 0:
        return 0.0
    return dot / (mag1 * mag2)

def handle_recommend(payload):
    # Payload format:
    # {
    #   "customer_id": int,
    #   "browse_history": [{"customer_id": int, "proid": int, "categid": int}],
    #   "purchase_history": [{"customer_id": int, "proid": int, "categid": int}],
    #   "products": [{"proid": int, "categid": int}],
    #   "popularity": {proid: int},
    #   "limit": int
    # }
    customer_id = payload.get("customer_id", 0)
    browse_history = payload.get("browse_history", [])
    purchase_history = payload.get("purchase_history", [])
    products = payload.get("products", [])
    popularity = payload.get("popularity", {})
    limit = payload.get("limit", 8)

    # Initialize scores for all available products
    scores = defaultdict(float)

    # 1. Base Score: Product Popularity (Normalized)
    max_pop = max(popularity.values()) if popularity else 1
    for p in products:
        pid = p["proid"]
        pop = popularity.get(str(pid), popularity.get(pid, 0.0))
        scores[pid] += (pop / max_pop) * 30.0  # Up to 30 points for popularity

    if customer_id > 0:
        # 2. Content-Based Filtering: Category Affinity Cosine Similarity
        # Compute user's category affinity vector based on views and purchases
        user_cat_vector = defaultdict(float)
        for bh in browse_history:
            if bh["customer_id"] == customer_id and bh["categid"]:
                user_cat_vector[bh["categid"]] += 1.0  # View adds 1.0

        for ph in purchase_history:
            if ph["customer_id"] == customer_id and ph["categid"]:
                user_cat_vector[ph["categid"]] += 3.0  # Purchase adds 3.0 (stronger signal)

        # Compute similarity between user profile and each product (which is a one-hot category vector)
        for p in products:
            pid = p["proid"]
            cat_id = p["categid"]
            if cat_id in user_cat_vector:
                # Simple Cosine similarity with one-hot vector [0..1..0] is just proportional to affinity
                product_vector = {cat_id: 1.0}
                sim = cosine_similarity(user_cat_vector, product_vector)
                scores[pid] += sim * 40.0  # Up to 40 points for content matching

        # 3. Collaborative Filtering (User-User KNN Style)
        # Find product associations from users who viewed the same things
        user_views = defaultdict(set)
        product_views = defaultdict(set)
        for bh in browse_history:
            uid = bh["customer_id"]
            pid = bh["proid"]
            if uid and pid:
                user_views[uid].add(pid)
                product_views[pid].add(uid)

        target_views = user_views.get(customer_id, set())
        if target_views:
            similar_users = defaultdict(float)
            for item in target_views:
                for peer in product_views.get(item, set()):
                    if peer != customer_id:
                        peer_views = user_views.get(peer, set())
                        # Jaccard similarity between target user and peer views
                        intersection = len(target_views & peer_views)
                        union = len(target_views | peer_views)
                        jaccard = intersection / union if union > 0 else 0
                        similar_users[peer] = max(similar_users[peer], jaccard)

            # Recommend items liked by similar users
            for peer, similarity in sorted(similar_users.items(), key=lambda x: x[1], reverse=True)[:5]:
                for item in user_views[peer]:
                    if item not in target_views:
                        scores[item] += similarity * 20.0  # Up to 20 points for collaborative views

        # 4. Exclude products already bought recently
        purchased_ids = {ph["proid"] for ph in purchase_history if ph["customer_id"] == customer_id}
        for pid in purchased_ids:
            if pid in scores:
                del scores[pid]

    # Sort and slice
    sorted_pids = [pid for pid, score in sorted(scores.items(), key=lambda x: x[1], reverse=True)]
    return sorted_pids[:limit]

def handle_detect_fraud(payload):
    # Payload format:
    # {
    #   "customer_id": int,
    #   "order_total": float,
    #   "past_order_payments": [float],
    #   "failed_payments_last_hour": int,
    #   "orders_last_10_minutes": int
    # }
    customer_id = payload.get("customer_id", 0)
    order_total = payload.get("order_total", 0.0)
    past_payments = payload.get("past_order_payments", [])
    failed_payments = payload.get("failed_payments_last_hour", 0)
    rapid_orders = payload.get("orders_last_10_minutes", 0)

    # Threshold checks
    max_failed_payments = 3
    if failed_payments >= max_failed_payments:
        return {
            "allowed": False,
            "risk": "high",
            "reason": "Multiple failed payment attempts in the last hour.",
            "alert": "failed_payments"
        }

    if rapid_orders >= 3:
        return {
            "allowed": False,
            "risk": "high",
            "reason": "Unusual pattern: 3+ orders within 10 minutes.",
            "alert": "rapid_orders"
        }

    # Z-Score Anomaly Detection on transaction amount
    if customer_id > 0 and len(past_payments) >= 3:
        mean, std = mean_std(past_payments)
        if std > 0:
            z_score = (order_total - mean) / std
            # If Z-score exceeds 3.0, it is statistically an outlier (99.7% confidence interval deviation)
            if z_score > 3.0 and order_total > 5000:
                return {
                    "allowed": True,  # Flag alert but allow check out (or set to false if high risk)
                    "risk": "medium",
                    "reason": f"Unusual order amount ₹{order_total:.2f} (Z-Score: {z_score:.2f}) deviates significantly from customer average (avg: ₹{mean:.2f}).",
                    "alert": "unusual_order_value"
                }

    return {
        "allowed": True,
        "risk": "low",
        "reason": "Transaction patterns are normal.",
        "alert": None
    }

def main():
    try:
        # Read parameters from command line or stdin
        input_data = sys.stdin.read()
        if not input_data:
            print(json.dumps({"error": "No input payload"}))
            return
        
        data = json.loads(input_data)
        task = data.get("task", "")
        payload = data.get("payload", {})

        if task == "recommend":
            result = handle_recommend(payload)
            print(json.dumps({"ok": True, "product_ids": result}))
        elif task == "detect_fraud":
            result = handle_detect_fraud(payload)
            print(json.dumps({"ok": True, "fraud_check": result}))
        else:
            print(json.dumps({"error": f"Unknown task: {task}"}))
    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
