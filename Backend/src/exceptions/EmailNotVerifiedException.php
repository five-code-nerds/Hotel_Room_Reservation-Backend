<?php

namespace Src\Exceptions;

use Exception;

class EmailNotVerifiedException extends Exception {
    protected $code = 403;
}
?>
