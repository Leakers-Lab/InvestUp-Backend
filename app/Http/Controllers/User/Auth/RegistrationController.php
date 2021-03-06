<?php

namespace App\Http\Controllers\User\Auth;

use App\Exceptions\LoginException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validateRequest($request);

        if (strlen($validated['phone']) == 9) $validated['phone'] = '998' . $validated['phone'];

        $validated['image'] = null;
        if (!empty($request->file('image'))) {
            $path = $request->file('image')->store('/', 'public');
            $validated['image'] = "https://server.investup.uz" .Storage::url($path);
        }

        $user = User::create([
            'first_name' => ucwords(strtolower($validated['first_name'])),
            'last_name' => ucwords(strtolower($validated['last_name'])),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => str_ireplace([' ', '(', ')', '+', '-'], '', $validated['phone']),
            'image' => $validated['image'],
        ]);

        $token = $user->createToken($user->name . '-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function validateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users',
            'phone' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        return $validator->validated();
    }
}
