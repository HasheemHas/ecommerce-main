# AI SEO Optimizer Service
from ai_microservice.utils.db import get_db_connection
import re

def analyze_seo(product_id: int):
    # 1. Fetch product details
    title = ""
    desc = ""
    cat_name = "Uncategorized"
    
    try:
        conn = get_db_connection()
        with conn.cursor() as cursor:
            sql = """
                SELECT p.PRODESC, p.PRONAME, c.CATEGORIES 
                FROM tblproduct p
                LEFT JOIN tblcategory c ON p.CATEGID = c.CATEGID
                WHERE p.PROID = %s
            """
            cursor.execute(sql, (product_id,))
            row = cursor.fetchone()
            if row:
                title = row.get("PRONAME") or row.get("PRODESC") or ""
                desc = row.get("PRODESC") or ""
                cat_name = row.get("CATEGORIES") or "Uncategorized"
    except Exception as e:
        print(f"Error fetching product for SEO: {e}")
        title = "Sample Product"
        desc = "This is a sample product description to score."
        
    # Analyze Title Length (40-60 chars is optimal)
    title_len = len(title)
    title_score = 100
    title_tips = []
    if title_len < 20:
        title_score = 60
        title_tips.append("Title is too short. Try to make it descriptive and include relevant keywords.")
    elif title_len > 80:
        title_score = 75
        title_tips.append("Title is too long. Search engines will truncate it in search results.")
    else:
        title_tips.append("Title length is optimal.")
        
    # Analyze Description Length (optimal is 150-300 words)
    words = desc.split()
    word_count = len(words)
    desc_score = 100
    desc_tips = []
    if word_count < 30:
        desc_score = 40
        desc_tips.append("Description is extremely thin. Expand details to improve search relevancy.")
    elif word_count < 100:
        desc_score = 75
        desc_tips.append("Description is slightly short. Aim for at least 150 words.")
    else:
        desc_tips.append("Description word count is healthy.")
        
    # Keyword Density calculation
    tokens = [w.lower() for w in words if len(w) > 3]
    unique_tokens = set(tokens)
    densities = {}
    for tok in unique_tokens:
        count = tokens.count(tok)
        densities[tok] = round((count / len(tokens)) * 100, 2) if tokens else 0
        
    high_density_words = sorted(densities.items(), key=lambda x: x[1], reverse=True)[:5]
    
    # Final SEO Score Calculation
    seo_score = int((title_score + desc_score) / 2)
    
    # Generate Meta Elements
    meta_title = f"Buy {title} Online | Best Price - H-Mart"
    meta_desc = desc[:150] + "..." if len(desc) > 150 else desc
    meta_keywords = ", ".join([cat_name.lower(), title.lower()] + [w for w, d in high_density_words])
    
    return {
        "product_id": product_id,
        "product_title": title,
        "seo_score": seo_score,
        "meta_title": meta_title,
        "meta_description": meta_desc,
        "meta_keywords": meta_keywords,
        "title_length": title_len,
        "word_count": word_count,
        "tips": title_tips + desc_tips,
        "top_keywords": [{"keyword": w, "density_pct": d} for w, d in high_density_words]
    }

def optimize_batch(product_ids: list):
    return [analyze_seo(pid) for pid in product_ids]
