<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['error' => null]);
    }
}
