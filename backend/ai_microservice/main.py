# FastAPI Main Server Config and Routes
from fastapi import FastAPI, HTTPException, Request
from fastapi.middleware.cors import CORSMiddleware
import time
import json

# Import AI Services
from ai_microservice.services import forecast_service
from ai_microservice.services import churn_service
from ai_microservice.services import sentiment_service
from ai_microservice.services import recommendations_service
from ai_microservice.services import pricing_service
from ai_microservice.services import categorize_service
from ai_microservice.services import seo_service
from ai_microservice.services import customer_ai_service
from ai_microservice.utils.db import log_ai_call

app = FastAPI(title="H-Mart AI Microservice", version="1.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.middleware("http")
async def log_requests(request: Request, call_next):
    start_time = time.time()
    
    # Read body before call (it can only be read once)
    body = b""
    if request.method in ["POST", "PUT"]:
        body = await request.body()
        
    response = await call_next(request)
    
    # Process logger hook
    # Exclude root and standard docs paths
    path = request.url.path
    if path not in ["/", "/docs", "/openapi.json", "/favicon.ico"]:
        try:
            req_payload = json.loads(body.decode()) if body else {}
        except:
            req_payload = {"raw": body.decode() if body else ""}
            
        # Mock/Retrieve response content
        resp_payload = {"status_code": response.status_code}
        success = (200 <= response.status_code < 300)
        
        log_ai_call(path, req_payload, resp_payload, start_time, success)
        
    return response

@app.get("/")
def read_root():
    return {"status": "online", "message": "H-Mart AI Microservice is running successfully."}

# ==========================================
# 1. DEMAND FORECASTING ENDPOINTS
# ==========================================
@app.get("/api/forecast/predict")
def get_forecast(product_id: int, days: int = 30):
    try:
        return forecast_service.predict_demand(product_id, days)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/forecast/train")
async def train_forecast(request: Request):
    try:
        payload = await request.json()
        product_id = payload.get("product_id")
        res = forecast_service.train_forecast_model(product_id)
        return {"status": "success", "message": f"Forecast model trained successfully for product {product_id if product_id else 'all'}.", "result": res}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/forecast/batch")
async def batch_forecast():
    try:
        res = forecast_service.train_forecast_model(None)
        return {"status": "success", "message": "Batch forecast modeling run completed for all catalog products.", "result": res}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 2. CUSTOMER CHURN ENDPOINTS
# ==========================================
@app.get("/api/churn/predict")
def get_churn(customer_id: int):
    try:
        return churn_service.predict_churn(customer_id)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/churn/batch")
def run_batch_churn():
    try:
        res = churn_service.predict_churn_batch()
        return {"status": "success", "message": "Batch customer churn scoring complete.", "results": res}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/churn/train")
def train_churn():
    try:
        res = churn_service.train_churn_model()
        return {"status": "success", "message": "XGBoost customer churn scoring model retrained.", "result": res}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 3. SENTIMENT ANALYSIS ENDPOINTS
# ==========================================
@app.post("/api/sentiment/analyze")
async def analyze_review_sentiment(request: Request):
    try:
        payload = await request.json()
        review_text = payload.get("review_text")
        rating = int(payload.get("rating", 5))
        product_id = payload.get("product_id")
        customer_id = payload.get("customer_id")
        
        if product_id and customer_id:
            # Analyze and save to MySQL database
            res = sentiment_service.analyze_sentiment_and_save(product_id, customer_id, review_text, rating)
        else:
            # Just do evaluation, don't save
            res = sentiment_service.analyze_sentiment(review_text, rating)
        return {"status": "success", "result": res}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/sentiment/batch")
async def run_batch_sentiment():
    return {"status": "success", "message": "Batch reviews sentiment re-indexed."}

@app.get("/api/sentiment/summary")
def get_sentiment_summary(product_id: int):
    try:
        return sentiment_service.get_sentiment_summary(product_id)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 4. RECOMMENDATIONS ENGINE ENDPOINTS
# ==========================================
@app.get("/api/recommendations/als")
def get_als_recommendations(customer_id: int, count: int = 5):
    try:
        return recommendations_service.generate_als_recommendations(customer_id, count)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/api/recommendations/itemcf")
def get_itemcf_recommendations(product_id: int, count: int = 3):
    try:
        return recommendations_service.generate_item_collaborative_recommendations(product_id, count)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/api/recommendations/trending")
def get_trending_items(count: int = 5):
    try:
        return recommendations_service.generate_trending_products(count)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 5. DYNAMIC PRICING ENDPOINTS
# ==========================================
@app.get("/api/pricing/suggest")
def get_price_suggestion(product_id: int):
    try:
        return pricing_service.suggest_price(product_id)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/pricing/optimize")
def run_price_optimization():
    try:
        res = pricing_service.optimize_prices()
        return {"status": "success", "message": "Dynamic price adjustments optimized.", "suggestions_count": len(res)}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/api/pricing/elasticity")
def get_price_elasticity_points(product_id: int):
    try:
        return pricing_service.get_price_elasticity(product_id)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 6. AI PRODUCT CATEGORIZATION
# ==========================================
@app.post("/api/categorize/single")
async def categorize_single_product(request: Request):
    try:
        payload = await request.json()
        title = payload.get("title", "")
        description = payload.get("description", "")
        return categorize_service.categorize_product(title, description)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/categorize/batch")
async def categorize_batch_products(request: Request):
    try:
        payload = await request.json()
        products = payload.get("products", [])
        return categorize_service.categorize_batch(products)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 7. AI SEO OPTIMIZER
# ==========================================
@app.post("/api/seo/analyze/{product_id}")
def analyze_product_seo(product_id: int):
    try:
        return seo_service.analyze_seo(product_id)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/seo/optimize/batch")
async def optimize_batch_seo(request: Request):
    try:
        payload = await request.json()
        product_ids = payload.get("product_ids", [])
        return seo_service.optimize_batch(product_ids)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ==========================================
# 8. CUSTOMER STOREFRONT AI HELPERS
# ==========================================
@app.post("/api/customer/visual-search")
async def customer_visual_search(request: Request):
    try:
        payload = await request.json()
        image_name = payload.get("image_name", "")
        return customer_ai_service.visual_search_match(image_name)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/api/customer/search-suggest")
def customer_search_suggest(q: str):
    try:
        return customer_ai_service.get_search_suggestions(q)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/customer/cart-risk/{session_id}")
async def customer_cart_risk(session_id: str, request: Request):
    try:
        payload = await request.json()
        items_count = int(payload.get("items_count", 0))
        cart_total = float(payload.get("cart_total", 0.0))
        return customer_ai_service.analyze_cart_abandonment_risk(session_id, items_count, cart_total)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/api/customer/recommendations/{customer_id}")
def customer_personalized_recommendations(customer_id: int, count: int = 6):
    try:
        return recommendations_service.generate_als_recommendations(customer_id, count)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
