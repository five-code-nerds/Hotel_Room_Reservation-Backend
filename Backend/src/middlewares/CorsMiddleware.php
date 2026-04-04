<?php

namespace Src\Middlewares;

class CorsMiddleware
{
    public static function handleCors($method)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, PUT, OPTIONS");
        if ($method === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
