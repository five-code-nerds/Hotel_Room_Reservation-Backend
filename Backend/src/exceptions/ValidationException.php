<?php

namespace Src\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $code = 400;
}
