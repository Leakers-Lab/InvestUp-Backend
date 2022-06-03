<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotBelongsException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PlansController extends Controller
{
    public function index(Request $request)
    {

    }

    public function addPlan(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|string',
            'company_id' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|numeric',
            'string' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $user->Companies()->Projects()->Plans()->create($validated);

        return response()->json(['error' => null]);
    }
}
