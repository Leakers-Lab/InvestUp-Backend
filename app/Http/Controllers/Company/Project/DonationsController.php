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
    public function create($alias, Request $request)
    {
        $project = Project::with('Plans')->where('alias', $alias)->first();
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|integer',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        Donation::create([
            'user_id' => $user->id,
            'plan_id' => $validated['plan_id'],
            'amount' => $validated(['amount']),
        ]);

        return response()->json(['error' => null]);
    }

     public function index($alias)
     {
         $project = Project::with('Donations.User')->where('alias', $alias)->first();

         return response()->json($project->Donations);
     }
}
