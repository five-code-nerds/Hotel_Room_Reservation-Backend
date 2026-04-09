<?php

    namespace Src\Middlewares;
    use Src\Core\JWTHandler;
    use Src\Exceptions\UnauthorizedException;

    class AuthMiddleware {
        public function isAdmin() {
            $token = trim($_SERVER['HTTP_AUTHORIZATION']);
            $jwtHandler = new JWTHandler();
            $payload = $jwtHandler->decode($token);
            if (!$token) {
                throw new UnauthorizedException("Token is required");
            }
            if ($payload->role !== 'admin') {
                throw new UnauthorizedException("Forbidden");
            }
        }
    }
?>