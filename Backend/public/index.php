<?php
require __DIR__ . '/../vendor/autoload.php';

use Src\Core\Router;
use Src\Middlewares\CorsMiddleware;
use Dotenv\Dotenv;

set_exception_handler(function ($error) {
    echo $error;
    $status_code = $error->getCode() ?? 500;
    http_response_code($status_code);
    echo json_encode(
        [
            "status" => "error",
            "message" => $error->getMessage()        
        ]
    );
});

CorsMiddleware::handleCors($_SERVER['REQUEST_METHOD']);
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$router = new Router();
header('Content-Type: application/json');
require __DIR__ . '/../src/routes/routes.php';
$router->resolve();
?>