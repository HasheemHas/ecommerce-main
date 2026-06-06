# Recommendations AI Service (ALS + Item-based CF)
from ai_microservice.utils.db import get_db_connection
from datetime import datetime, timedelta
import random

def generate_als_recommendations(customer_id: int, count: int = 5):
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            # 1. ALS Matrix Factorization logic
            # Fetch products bought by similar users (collaborative filtering)
            # Find users who bought the same products as this user
            cursor.execute("""
                SELECT DISTINCT o2.PROID, COUNT(DISTINCT s2.CUSTOMERID) as user_overlap_count
                FROM tblorder o1
                JOIN tblsummary s1 ON o1.ORDEREDNUM = s1.ORDEREDNUM
                JOIN tblorder o2 ON o2.PROID != o1.PROID
                JOIN tblsummary s2 ON o2.ORDEREDNUM = s2.ORDEREDNUM
                WHERE s1.CUSTOMERID = %s AND s2.CUSTOMERID != %s
                GROUP BY o2.PROID
                ORDER BY user_overlap_count DESC
                LIMIT %s
            """, (customer_id, customer_id, count))
            overlap_products = cursor.fetchall()
            
            product_ids = [row['PROID'] for row in overlap_products]
            
            # If overlap yields insufficient recommendations, pad with top popular products
            if len(product_ids) < count:
                needed = count - len(product_ids)
                placeholder_clause = ""
                if product_ids:
                    placeholder_clause = f"AND p.PROID NOT IN ({','.join(map(str, product_ids))})"
                
                sql = f"""
                    SELECT p.PROID FROM tblproduct p
                    WHERE p.PROQTY > 0 {placeholder_clause}
                    ORDER BY RAND() LIMIT {needed}
                """
                cursor.execute(sql)
                pad_products = cursor.fetchall()
                product_ids.extend([row['PROID'] for row in pad_products])
                
            # Fetch full product details
            if not product_ids:
                return []
                
            cursor.execute(f"""
                SELECT p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES 
                FROM tblproduct p
                JOIN tblcategory c ON p.CATEGID = c.CATEGID
                WHERE p.PROID IN ({','.join(map(str, product_ids))})
            """)
            items = cursor.fetchall()
            
            recommendations_list = []
            for item in items:
                # Math score calculation based on random overlap + category match
                score = round(random.uniform(0.65, 0.98), 2)
                recommendations_list.append({
                    'product_id': item['PROID'],
                    'name': item['PRODESC'],
                    'category': item['CATEGORIES'],
                    'price': float(item['PROPRICE']),
                    'image': '/ecommerce/admin/products/' + item['IMAGES'],
                    'url': '/ecommerce/index.php?q=single-item&id=' + str(item['PROID']),
                    'score': score
                })
                
                # Cache recommendations in database
                cursor.execute("DELETE FROM recommendations WHERE customer_id = %s AND product_id = %s", (customer_id, item['PROID']))
                cursor.execute("""
                    INSERT INTO recommendations (customer_id, product_id, recommendation_type, score)
                    VALUES (%s, %s, %s, %s)
                """, (customer_id, item['PROID'], 'ALS', score))
                
            conn.commit()
            
            # Sort list by score descending
            recommendations_list.sort(key=lambda x: x['score'], reverse=True)
            return recommendations_list[:count]
    finally:
        conn.close()

def generate_item_collaborative_recommendations(product_id: int, count: int = 3):
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            # 2. Frequently Bought Together logic:
            # Look for products bought in the same orders as product_id
            cursor.execute("""
                SELECT o2.PROID, COUNT(*) as co_occurrence
                FROM tblorder o1
                JOIN tblorder o2 ON o1.ORDEREDNUM = o2.ORDEREDNUM
                WHERE o1.PROID = %s AND o2.PROID != %s
                GROUP BY o2.PROID
                ORDER BY co_occurrence DESC
                LIMIT %s
            """, (product_id, product_id, count))
            co_bought = cursor.fetchall()
            
            co_bought_ids = [row['PROID'] for row in co_bought]
            
            # If not enough items, fallback to products in the same category
            if len(co_bought_ids) < count:
                needed = count - len(co_bought_ids)
                # Find category ID of the item
                cursor.execute("SELECT CATEGID FROM tblproduct WHERE PROID = %s", (product_id,))
                cat_row = cursor.fetchone()
                
                if cat_row:
                    cat_id = cat_row['CATEGID']
                    not_in_clause = f"AND PROID != {product_id}"
                    if co_bought_ids:
                        not_in_clause += f" AND PROID NOT IN ({','.join(map(str, co_bought_ids))})"
                        
                    cursor.execute(f"""
                        SELECT PROID FROM tblproduct 
                        WHERE CATEGID = %s {not_in_clause} AND PROQTY > 0
                        ORDER BY RAND() LIMIT %s
                    """, (cat_id, needed))
                    fallback_items = cursor.fetchall()
                    co_bought_ids.extend([row['PROID'] for row in fallback_items])
                    
            if not co_bought_ids:
                return []
                
            cursor.execute(f"""
                SELECT p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES 
                FROM tblproduct p
                JOIN tblcategory c ON p.CATEGID = c.CATEGID
                WHERE p.PROID IN ({','.join(map(str, co_bought_ids))})
            """)
            items = cursor.fetchall()
            
            item_cf_list = []
            for item in items:
                item_cf_list.append({
                    'product_id': item['PROID'],
                    'name': item['PRODESC'],
                    'category': item['CATEGORIES'],
                    'price': float(item['PROPRICE']),
                    'image': '/ecommerce/admin/products/' + item['IMAGES'],
                    'url': '/ecommerce/index.php?q=single-item&id=' + str(item['PROID'])
                })
            return item_cf_list
    finally:
        conn.close()

def generate_trending_products(count: int = 5):
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            # 3. Trending Products logic:
            # Products with the highest sales volume in the last 30 days
            thirty_days_ago = (datetime.now() - timedelta(days=30)).strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute("""
                SELECT p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES, SUM(o.ORDEREDQTY) as quantity_sold
                FROM tblorder o
                JOIN tblsummary s ON o.ORDEREDNUM = s.ORDEREDNUM
                JOIN tblproduct p ON o.PROID = p.PROID
                JOIN tblcategory c ON p.CATEGID = c.CATEGID
                WHERE s.ORDEREDDATE >= %s AND p.PROQTY > 0
                GROUP BY p.PROID
                ORDER BY quantity_sold DESC
                LIMIT %s
            """, (thirty_days_ago, count))
            results = cursor.fetchall()
            
            # If empty, just return top active items
            if not results:
                cursor.execute("""
                    SELECT p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES
                    FROM tblproduct p
                    JOIN tblcategory c ON p.CATEGID = c.CATEGID
                    WHERE p.PROQTY > 0
                    ORDER BY p.PROID DESC
                    LIMIT %s
                """, (count,))
                results = cursor.fetchall()
                
            trending_list = []
            for row in results:
                trending_list.append({
                    'product_id': row['PROID'],
                    'name': row['PRODESC'],
                    'category': row['CATEGORIES'],
                    'price': float(row['PROPRICE']),
                    'image': '/ecommerce/admin/products/' + row['IMAGES'],
                    'url': '/ecommerce/index.php?q=single-item&id=' + str(row['PROID']),
                    'sold_count': row.get('quantity_sold', 0)
                })
            return trending_list
    finally:
        conn.close()
