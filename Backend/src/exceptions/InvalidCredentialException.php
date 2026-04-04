<?php

namespace Src\Exceptions;

use Exception;

class InvalidCredentialException extends Exception
{
    protected $code = 401;
}
?>