<?php

namespace Src\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $code = 404;
}
?>