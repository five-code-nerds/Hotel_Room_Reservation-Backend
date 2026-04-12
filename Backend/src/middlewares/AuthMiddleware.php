<?php

    namespace Src\Middlewares;
    use Src\Core\JWTHandler;
    use Src\Exceptions\UnauthorizedException;

    class AuthMiddleware {
        public function authHandle() {
            $requestHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? "";
            if(!$requestHeader) {
               $_REQUEST['user'] = null;
               return;
            }
            $token = trim(str_replace('Bearer ', '', $requestHeader));
            $jwtHandler = new JWTHandler();
            $payload = $jwtHandler->decode($token);
            if (!$payload) {
                throw new UnauthorizedException("Invalid token");
            }    
            $_REQUEST['user'] = $payload;        
        }
    }
?>