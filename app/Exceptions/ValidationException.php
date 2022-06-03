<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function render()
    {
        return response()->json(['errors' => json_decode($this->getMessage())], 403);
    }
}
