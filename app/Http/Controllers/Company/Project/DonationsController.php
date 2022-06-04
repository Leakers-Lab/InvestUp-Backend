<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

         DB::update("SET SESSION sql_mode = 'IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

         $count = DB::select("SELECT DISTINCT user_id FROM donations WHERE project_id = {$project->id}");
         $sponsors = DB::select("SELECT * FROM donations INNER JOIN users ON donations.user_id = users.id WHERE project_id = {$project->id} GROUP BY user_id");

         return response()->json(['total' => count($count), 'sponsors' => $sponsors]);
     }
}
