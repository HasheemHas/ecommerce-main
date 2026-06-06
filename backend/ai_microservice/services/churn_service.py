# Customer Churn Prediction Service using XGBoost logic
from datetime import datetime
import json
from ai_microservice.utils.db import get_db_connection

def train_churn_model():
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            # 1. Fetch all customers
            cursor.execute("SELECT CUSTOMERID, FNAME, LNAME FROM tblcustomer")
            customers = cursor.fetchall()
            
            # Delete existing churn scores
            cursor.execute("DELETE FROM churn_scores")
            
            for cust in customers:
                cid = cust['CUSTOMERID']
                
                # 2. Pull RFM metrics (Recency, Frequency, Monetary)
                cursor.execute("""
                    SELECT 
                        MAX(ORDEREDDATE) as last_order_date,
                        COUNT(DISTINCT ORDEREDNUM) as total_orders,
                        SUM(PAYMENT) as total_spent
                    FROM tblsummary
                    WHERE CUSTOMERID = %s
                """, (cid,))
                metrics = cursor.fetchone()
                
                # Default baseline values
                recency_days = 365.0
                frequency = 0
                monetary = 0.0
                
                if metrics and metrics['last_order_date']:
                    last_date = metrics['last_order_date']
                    # Calculate difference in days between last order and today
                    recency_days = (datetime.now() - last_date).days
                    frequency = int(metrics['total_orders'])
                    monetary = float(metrics['total_spent'])
                
                # 3. XGBoost Simulation Math
                # Sigmoid classification function: P(churn) = 1 / (1 + exp(-x))
                # x is modeled on RFM features
                x = 0.0
                factors = []
                
                # Recency increases churn probability
                if recency_days > 90:
                    x += (recency_days - 90) * 0.02
                    factors.append(f"No purchases in last {recency_days} days")
                elif recency_days < 14:
                    x -= 1.5 # Recency active
                
                # Frequency decreases churn probability
                if frequency >= 5:
                    x -= 1.0
                elif frequency == 1:
                    x += 0.5
                    factors.append("One-time purchaser")
                elif frequency == 0:
                    x += 1.5
                    factors.append("No purchase activity registered")
                    
                # Monetary value impact
                if monetary > 5000:
                    x -= 0.5
                    
                # Compute churn probability (Sigmoid function)
                import math
                prob = 1.0 / (1.0 + math.exp(-x))
                churn_probability = round(prob * 100.0, 2)
                
                # Determine risk level
                if churn_probability < 30.0:
                    risk_level = "Low"
                elif churn_probability < 70.0:
                    risk_level = "Medium"
                else:
                    risk_level = "High"
                    
                # Limit factors list
                top_factors = json.dumps(factors[:3])
                
                # Store scored parameters
                cursor.execute("""
                    INSERT INTO churn_scores (customer_id, churn_probability, risk_level, top_risk_factors)
                    VALUES (%s, %s, %s, %s)
                """, (cid, churn_probability, risk_level, top_factors))
                
            conn.commit()
    finally:
        conn.close()
    return True

def predict_churn(customer_id: int):
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM churn_scores WHERE customer_id = %s LIMIT 1", (customer_id,))
            result = cursor.fetchone()
            
            if not result:
                train_churn_model()
                cursor.execute("SELECT * FROM churn_scores WHERE customer_id = %s LIMIT 1", (customer_id,))
                result = cursor.fetchone()
                
            cursor.execute("SELECT FNAME, LNAME FROM tblcustomer WHERE CUSTOMERID = %s", (customer_id,))
            cust = cursor.fetchone()
            customer_name = f"{cust['FNAME']} {cust['LNAME']}" if cust else "Customer"
            
            if result:
                return {
                    'customer_id': customer_id,
                    'customer_name': customer_name,
                    'churn_probability': result['churn_probability'],
                    'risk_level': result['risk_level'],
                    'top_risk_factors': json.loads(result['top_risk_factors']),
                    'evaluated_at': result['evaluated_at'].strftime('%Y-%m-%d %H:%M:%S')
                }
            else:
                return {
                    'customer_id': customer_id,
                    'customer_name': customer_name,
                    'churn_probability': 50.0,
                    'risk_level': 'Medium',
                    'top_risk_factors': ['No historical records'],
                    'evaluated_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                }
    finally:
        conn.close()

def predict_churn_batch():
    train_churn_model()
    conn = get_db_connection()
    try:
        with conn.cursor() as cursor:
            cursor.execute("""
                SELECT c.CUSTOMERID, c.FNAME, c.LNAME, cs.churn_probability, cs.risk_level, cs.top_risk_factors, cs.evaluated_at
                FROM churn_scores cs
                JOIN tblcustomer c ON cs.customer_id = c.CUSTOMERID
                ORDER BY cs.churn_probability DESC
            """)
            rows = cursor.fetchall()
            
            batch_data = []
            for row in rows:
                batch_data.append({
                    'customer_id': row['CUSTOMERID'],
                    'customer_name': f"{row['FNAME']} {row['LNAME']}",
                    'churn_probability': row['churn_probability'],
                    'risk_level': row['risk_level'],
                    'top_risk_factors': json.loads(row['top_risk_factors']),
                    'evaluated_at': row['evaluated_at'].strftime('%Y-%m-%d %H:%M:%S')
                })
            return batch_data
    finally:
        conn.close()
