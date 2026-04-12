<?php

namespace Src\Middlewares;

use Src\Exceptions\UnauthorizedException;

class AdminMiddleware
{
    public function isAdmin()
    {
        $user = $_REQUEST['user'] ?? "";
        if (!$user) {
            throw new UnauthorizedException("Unauthorized");
        }
        if ($user->role !== 'admin') {
            throw new UnauthorizedException("Forbidden");
        }
    }
}
?>