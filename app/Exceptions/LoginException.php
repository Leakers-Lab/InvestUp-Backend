<?php

namespace App\Exceptions;

use Exception;

class LoginException extends Exception
{
    public function render()
    {
        return response()->json(['error' => $this->getMessage()], 401);
    }
}
