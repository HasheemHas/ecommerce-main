#!/bin/bash

# Render supplies a dynamic port. Apache otherwise defaults to port 80.
APP_PORT="${PORT:-80}"
sed -i "s/^Listen .*/Listen ${APP_PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${APP_PORT}>/" /etc/apache2/sites-available/000-default.conf

# Catalog seeding is destructive and expensive, so it must be explicitly opted
# into for a first-time database. Normal deploys never reseed the catalog.
if [ "${RUN_DB_MIGRATIONS:-0}" = "1" ]; then
    echo "Running one-time database migration..."
    python /var/www/html/backend/db_migration.py
else
    echo "Skipping database seed (set RUN_DB_MIGRATIONS=1 for first-time setup)."
fi

# Start the Python FastAPI microservice in the background
echo "Starting Python FastAPI Microservice..."
cd /var/www/html/backend
uvicorn ai_microservice.main:app --host 127.0.0.1 --port 8000 &

# Start Apache web server in the foreground
echo "Starting Apache Web Server..."
exec apache2-foreground
