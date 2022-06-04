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

    public function create(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'plans.*' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        foreach ($validated['plans'] as $plan) {
            $project = Project::find($plan['project_id']);
            $plan['company_id'] = $project->Company->id;
            $project->Plans()->create($plan);
        }

        return response()->json(['error' => null]);
    }
}
