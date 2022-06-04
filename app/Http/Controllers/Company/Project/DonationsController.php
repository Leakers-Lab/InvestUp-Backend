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

    }

     public function index($alias)
     {
         $project = Project::with('Donations.User')->where('alias', $alias)->first();

         return response()->json($project->Donations);
     }
}
