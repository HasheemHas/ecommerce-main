# AI Product Categorization Service
from ai_microservice.utils.db import get_db_connection
import re

def clean_text(text):
    if not text:
        return ""
    return re.sub(r'[^\w\s]', '', text.lower())

def categorize_product(title: str, description: str):
    title_clean = clean_text(title)
    desc_clean = clean_text(description)
    combined = title_clean + " " + desc_clean
    
    # 1. Fetch categories from tblcategory
    categories = []
    try:
        conn = get_db_connection()
        with conn.cursor() as cursor:
            cursor.execute("SELECT CATEGID, CATEGORIES FROM tblcategory")
            categories = cursor.fetchall()
        conn.close()
    except Exception as e:
        print(f"Error fetching categories: {e}")
        # fallback categories if db connection fails
        categories = [
            {"CATEGID": 1, "CATEGORIES": "Fashion"},
            {"CATEGORIES": "Electronics", "CATEGID": 2},
            {"CATEGORIES": "Groceries", "CATEGID": 3}
        ]

    best_match = None
    max_score = 0
    
    # Simple token matching / confidence score logic
    for cat in categories:
        cat_name = cat["CATEGORIES"]
        cat_tokens = clean_text(cat_name).split()
        
        # Match tokens
        score = 0
        for token in cat_tokens:
            # Title matches have higher weight
            score += title_clean.split().count(token) * 3
            score += desc_clean.split().count(token)
            
        if score > max_score:
            max_score = score
            best_match = cat
            
    # Default to first category if no match
    if not best_match and categories:
        best_match = categories[0]
        
    confidence = min(100.0, float(max_score * 20.0 + 35.0)) if max_score > 0 else 25.0
    
    return {
        "category_id": best_match["CATEGID"] if best_match else 1,
        "category_name": best_match["CATEGORIES"] if best_match else "Uncategorized",
        "confidence_score": confidence
    }

def categorize_batch(products: list):
    results = []
    for p in products:
        title = p.get("title", "")
        desc = p.get("description", "")
        pro_id = p.get("product_id")
        res = categorize_product(title, desc)
        if pro_id:
            res["product_id"] = pro_id
        results.append(res)
    return results
