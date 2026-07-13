# Database utility — reads credentials from ENV vars (production) or PHP config.php (local)
import os
import re
import time
import json
import pymysql

def get_db_config():
    # --- PRODUCTION: use environment variables set on Render ---
    env_host = os.environ.get('DB_HOST')
    env_user = os.environ.get('DB_USER')
    env_pass = os.environ.get('DB_PASS', '')
    env_db   = os.environ.get('DB_NAME')
    env_port = int(os.environ.get('DB_PORT', '3306'))

    if env_host and env_user and env_db:
        return {
            'host':     env_host,
            'user':     env_user,
            'password': env_pass,
            'database': env_db,
            'port': env_port
        }

    # --- LOCAL FALLBACK: parse PHP include/config.php ---
    base_dir    = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
    config_path = os.path.join(base_dir, 'include', 'config.php')

    config = {
        'host':     'localhost',
        'user':     'root',
        'password': '',
        'database': 'db_ecommerce',
        'port': 3306
    }

    if os.path.exists(config_path):
        try:
            with open(config_path, 'r') as f:
                content = f.read()

                server_match = re.search(r'define\s*\(\s*[\'"]server[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]', content, re.IGNORECASE)
                user_match   = re.search(r'define\s*\(\s*[\'"]user[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]', content, re.IGNORECASE)
                pass_match   = re.search(r'define\s*\(\s*[\'"]pass[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]', content, re.IGNORECASE)
                db_match     = re.search(r'define\s*\(\s*[\'"]database_name[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]', content, re.IGNORECASE)

                if server_match: config['host']     = server_match.group(1)
                if user_match:   config['user']     = user_match.group(1)
                if pass_match:   config['password'] = pass_match.group(1)
                if db_match:     config['database'] = db_match.group(1)
        except Exception as e:
            print(f"Error parsing config.php: {e}")

    return config


def get_db_connection():
    cfg = get_db_config()
    return pymysql.connect(
        host=cfg['host'],
        user=cfg['user'],
        password=cfg['password'],
        database=cfg['database'],
        port=cfg.get('port', 3306),
        cursorclass=pymysql.cursors.DictCursor,
        connect_timeout=10
    )


def log_ai_call(endpoint: str, request_payload: dict, response_payload: dict, start_time: float, success: bool = True):
    duration = int((time.time() - start_time) * 1000)
    try:
        conn = get_db_connection()
        with conn.cursor() as cursor:
            sql = """
                INSERT INTO `python_ai_logs`
                (`endpoint`, `request_payload`, `response_payload`, `execution_time_ms`, `success`)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(sql, (
                endpoint,
                json.dumps(request_payload),
                json.dumps(response_payload),
                duration,
                1 if success else 0
            ))
            conn.commit()
        conn.close()
    except Exception as e:
        print(f"Error logging AI call: {e}")
