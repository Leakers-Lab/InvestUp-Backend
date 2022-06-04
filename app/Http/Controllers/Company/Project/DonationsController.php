<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationsController extends Controller
{
    public function create($alias, Request $request)
    {
        $project = Project::where('alias', $alias)->first();
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $project->Plans()->Donations()->create([
            'user_id' => $user->id,
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
