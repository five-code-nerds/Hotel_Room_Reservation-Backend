<?php
require __DIR__ . '/../vendor/autoload.php';

use Src\Core\Router;
use Src\Middlewares\CorsMiddleware;
use Dotenv\Dotenv;

set_exception_handler(function ($error) {

    $logMessage = "[". date("Y-m-d H:i:s") . "] " . $error->getMessage(). " in ". $error->getFile() . " : Line " . $error->getLine() . "\n" . $error->getTraceAsString() . "\n";
    $logDir = __DIR__."/../logs";
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $errorCode = $error->getCode();
    $status_code = ($errorCode >= 400 && $errorCode < 600) ? $errorCode : 500;
    
    if ($status_code === 500) {
        file_put_contents($logDir . "/errors.log", $logMessage, FILE_APPEND);
    }
    http_response_code($status_code);
    echo json_encode (
        [
            "status" => "error",
            "message" => ($status_code === 500) ? "Internal server error" : $error->getMessage()        
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