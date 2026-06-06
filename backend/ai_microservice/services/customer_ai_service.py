# Customer Storefront AI Helpers
from ai_microservice.utils.db import get_db_connection
import random

def levenshtein_distance(s1, s2):
    if len(s1) < len(s2):
        return levenshtein_distance(s2, s1)
    if len(s2) == 0:
        return len(s1)
    
    previous_row = range(len(s2) + 1)
    for i, c1 in enumerate(s1):
        current_row = [i + 1]
        for j, c2 in enumerate(s2):
            insertions = previous_row[j + 1] + 1
            deletions = current_row[j] + 1
            substitutions = previous_row[j] + (c1 != c2)
            current_row.append(min(insertions, deletions, substitutions))
        previous_row = current_row
        
    return previous_row[-1]

def visual_search_match(image_name: str):
    # Simulated Tag Extraction from image name
    img_lower = image_name.lower()
    tags = []
    if "shoe" in img_lower or "snkr" in img_lower or "boot" in img_lower:
        tags = ["shoes", "reebok", "nike", "adidas"]
    elif "dress" in img_lower or "shirt" in img_lower or "jean" in img_lower or "wear" in img_lower:
        tags = ["casual", "sleeveless", "printed", "shirt", "pants"]
    elif "electronics" in img_lower or "phone" in img_lower or "tv" in img_lower or "tech" in img_lower:
        tags = ["smart", "phone", "led", "display"]
    else:
        # Generic color tags
        tags = ["premium", "casual", "printed"]
        
    matched_products = []
    try:
        conn = get_db_connection()
        with conn.cursor() as cursor:
            # Match any of the extracted visual tags in the product name
            conditions = " OR ".join([f"PRONAME LIKE %s OR PRODESC LIKE %s" for _ in tags])
            if not conditions:
                conditions = "1=1"
            sql = f"SELECT PROID, PRONAME, PRODESC, PROPRICE, IMAGES FROM tblproduct WHERE {conditions} LIMIT 6"
            
            params = []
            for t in tags:
                params.append(f"%{t}%")
                params.append(f"%{t}%")
                
            cursor.execute(sql, tuple(params))
            matched_products = cursor.fetchall()
        conn.close()
    except Exception as e:
        print(f"Error executing visual search: {e}")
        
    # If no matches, return a couple of random items
    if not matched_products:
        try:
            conn = get_db_connection()
            with conn.cursor() as cursor:
                cursor.execute("SELECT PROID, PRONAME, PRODESC, PROPRICE, IMAGES FROM tblproduct ORDER BY RAND() LIMIT 4")
                matched_products = cursor.fetchall()
            conn.close()
        except:
            pass

    return {
        "status": "success",
        "detected_tags": tags,
        "results": [
            {
                "product_id": p.get("PROID"),
                "name": p.get("PRONAME") or p.get("PRODESC"),
                "price": p.get("PROPRICE"),
                "image": p.get("IMAGES")
            }
            for p in matched_products
        ]
    }

def get_search_suggestions(query: str):
    query_lower = query.lower()
    
    # 1. Fetch catalog terms to match Levenshtein / autocomplete
    catalog_names = []
    try:
        conn = get_db_connection()
        with conn.cursor() as cursor:
            cursor.execute("SELECT DISTINCT PRODESC FROM tblproduct")
            rows = cursor.fetchall()
            catalog_names = [r["PRODESC"] for r in rows if r["PRODESC"]]
        conn.close()
    except Exception as e:
        print(f"Error fetching suggestions index: {e}")
        
    # Find corrections (distance <= 3) and direct starts_with prefixes
    corrections = []
    autocompletes = []
    
    for term in catalog_names:
        term_clean = term.lower()
        if term_clean.startswith(query_lower):
            autocompletes.append(term)
            
        words = term_clean.split()
        for w in words:
            dist = levenshtein_distance(query_lower, w)
            if 0 < dist <= 2 and w not in corrections:
                corrections.append(w)
                
    return {
        "query": query,
        "corrected_spelling": corrections[0] if corrections else None,
        "suggestions": autocompletes[:6],
        "related_searches": corrections[:5]
    }

def analyze_cart_abandonment_risk(session_id: str, items_count: int, cart_total: float):
    # Predict cart abandonment risk index (0 to 100) based on behavior factors
    # e.g., high total and few items usually shows hesitance / comparison shopping
    risk_score = 50.0
    if cart_total > 5000:
        risk_score += 20.0
    if items_count == 1:
        risk_score += 15.0
    if cart_total < 500:
        risk_score -= 15.0
        
    risk_score = min(100.0, max(0.0, risk_score))
    
    # Auto coupon incentive suggestion
    coupon_offered = None
    discount_pct = 0
    if risk_score >= 70.0:
        coupon_offered = "SAVE15"
        discount_pct = 15
    elif risk_score >= 50.0:
        coupon_offered = "SAVE10"
        discount_pct = 10
        
    return {
        "session_id": session_id,
        "abandonment_risk_pct": risk_score,
        "risk_level": "High" if risk_score >= 70 else ("Medium" if risk_score >= 40 else "Low"),
        "trigger_coupon": coupon_offered,
        "discount_pct": discount_pct,
        "urgency_msg": "Hurry! Items in your cart are selling fast. Check out now to secure your stock."
    }
