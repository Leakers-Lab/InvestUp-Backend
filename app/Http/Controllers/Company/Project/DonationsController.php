<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationsController extends Controller
{
    public function create(Request $request, $alias)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'nullable|integer',
            'amount' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $project = Project::where('alias', $alias)->first();

        $plan = $project->Plans()->find($validated['plan_id'] ?? null);
        if ($plan) {
            $donate = $plan->Donations()->create([
                'project_id' => $project->id,
                'user_id' => $request->user()->id,
                'amount' => $plan->price,
                'status' => 'success'
            ]);
        } else {
            $donate = Donation::create([
                'plan_id' => null,
                'project_id' => $project->id,
                'user_id' => $request->user()->id,
                'amount' => $validated['amount'],
                'status' => 'success'
            ]);
        }

        return response()->json($donate);
    }

     public function index($alias)
     {
         $project = Project::with('Donations.User')->where('alias', $alias)->first();

         return response()->json($project->Donations);
     }
}
