<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with('Companies.Projects')->find($request->user()->id);

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $path = $request->file('image')->store('/', 'public');

        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable',
            'password' => 'nullable|confirmed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $validated['image'] = Storage::url($path) ?? null;

        $user->update($validated);

        return response()->json($user);
    }
}
