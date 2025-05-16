<?php

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
require_once dirname(__DIR__, 1) . '/bootstrap/bootstrap.php';

use Src\Http\ResourceController;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$resource = $_GET['resource'] ?? null;

try {
    $controller = new ResourceController();
    $response = $controller->handle($resource);
    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage(),
    ]);
}
