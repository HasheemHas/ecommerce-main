#!/bin/bash

# Run database migrations and seed products in Python
echo "Running Python database migrations..."
python /var/www/html/backend/db_migration.py

# Start the Python FastAPI microservice in the background
echo "Starting Python FastAPI Microservice..."
cd /var/www/html/backend
uvicorn ai_microservice.main:app --host 127.0.0.1 --port 8000 &

# Start Apache web server in the foreground
echo "Starting Apache Web Server..."
exec apache2-foreground
