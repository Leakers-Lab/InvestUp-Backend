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
            'plan_id' => 'required|integer',
            'amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $project = Project::where('alias', $alias)->first();
        $donate = $project->Plans()->find($validated['plan_id'])->Donations()->create([
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
            'amount' => $validated['amount']
        ]);

        return response()->json($donate);
    }

     public function index($alias)
     {
         $project = Project::with('Donations.User')->where('alias', $alias)->first();

         return response()->json($project->Donations);
     }
}
