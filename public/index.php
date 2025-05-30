<?php

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
require_once dirname(__DIR__, 1) . '/bootstrap/bootstrap.php';

use App\Http\ResourceController;

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get 'resource' from the query string, or null if not set
$resource = $_GET['resource'] ?? null;

try {
    $controller = new ResourceController();

    // Handle the request and return the resource data
    $response = $controller->handle($resource);

    // Output the response as JSON
    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage(),
    ]);
}
