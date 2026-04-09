<?php
    namespace Src\Exceptions;
    use Exception;
    class UnauthorizedException extends Exception {
        protected $code = 403;
    }
?>