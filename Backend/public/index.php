<?php
require __DIR__ . '/../vendor/autoload.php';

use Src\Core\Router;

set_exception_handler(function ($error) {
    $status_code = $error->getCode() ?? 500;
    http_response_code($status_code);
    echo json_encode(
        [
            "status" => "error",
            "message" => $error->getMessage()
        ]
    );
});
$router = new Router();
require __DIR__ . '/../src/routes/routes.php';
$router->resolve();
?>