<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotBelongsException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PlansController extends Controller
{
    public function index($alias)
    {
        $project = Project::where('alias', $alias)->first();

        $plans = $project->Plans;

        return response()->json($plans);
    }

    public function create($alias, Request $request)
    {
        $user = $request->user();
        $project = Project::where('alias', $alias)->first();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $user->Companies()->find($project->Company->id)->Projects()->where('alias', $alias)->Plans()->create($validated);

        return response()->json(['error' => null]);
    }
}
