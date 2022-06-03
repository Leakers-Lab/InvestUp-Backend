<?php

namespace App\Http\Controllers\Company\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'active')->get();

        return response()->json($projects, 200);
    }
}
