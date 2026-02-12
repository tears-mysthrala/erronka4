<?php
/**
 * Simple API Ping Test
 * URL: /api/ping.php
 */

// CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Simple ping response
$response = [
    'status' => 'ok',
    'message' => 'API is working',
    'timestamp' => time(),
    'method' => $_SERVER['REQUEST_METHOD'],
    'php_version' => PHP_VERSION
];

// If POST, echo back the received data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $response['received'] = $data;
}

echo json_encode($response);
