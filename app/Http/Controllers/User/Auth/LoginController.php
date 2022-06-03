<?php

namespace App\Http\Controllers\User\Auth;

use App\Exceptions\LoginException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validateRequest($request);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            throw new LoginException('User not found');
        }

        if (!Hash::check($validated['password'], $user->password)) {
            throw new LoginException('Password is incorrect');
        }

        $token = $user->createToken($user->name . '-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function validateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        return $validator->validated();
    }
}
