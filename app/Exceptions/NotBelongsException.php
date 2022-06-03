<?php

namespace App\Exceptions;

use Exception;

class NotBelongsException extends Exception
{
    public function render()
    {
        return response()->json(['error' => $this->getMessage()], 403);
    }
}
