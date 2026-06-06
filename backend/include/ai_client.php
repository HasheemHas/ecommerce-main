<?php
/**
 * PHP Client for the FastAPI AI Microservice.
 * Auto-detects environment:
 *   - Local (XAMPP): connects to localhost:8000
 *   - Production (InfinityFree): connects to Render-hosted API
 */

// ---------------------------------------------------------------
// YOUR RENDER API URL — update this after you deploy to Render!
// Example: https://hmart-ai-api.onrender.com
// ---------------------------------------------------------------
define('AI_MICROSERVICE_URL', 'https://hmart-ai-api.onrender.com');

class AIClient
{
    private static function getBaseUrl()
    {
        // Detect if running on localhost
        $is_local = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = strtolower($_SERVER['HTTP_HOST']);
            if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
                $is_local = true;
            }
        }
        return $is_local ? 'http://localhost:8000' : AI_MICROSERVICE_URL;
    }

    public static function call($endpoint, $method = 'GET', $payload = null)
    {
        $base = self::getBaseUrl();
        $url  = $base . $endpoint;
        
        // 1. Try to make the API call
        $response = self::makeRequest($url, $method, $payload);
        
        if ($response === false) {
            return [
                'ok'    => false,
                'error' => 'AI microservice is not responding. It may be waking up (cold start) — please try again in 30 seconds.'
            ];
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'ok' => false,
                'error' => 'Invalid JSON returned from Python microservice.',
                'raw' => $response
            ];
        }
        
        return $data;
    }
    
    private static function makeRequest($url, $method, $payload)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // allow time for Render cold start
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300 && $res !== false) {
            return $res;
        }
        return false;
    }
    
}
?>
