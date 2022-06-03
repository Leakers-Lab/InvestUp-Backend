<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $project = Project::with(['Category', 'Company'])->where('alias', $request['alias'])->where('status', 'active')->first();

        if (!$project) {
            throw new NotFoundException('Project not found');
        }

        $formatted = [
            'id' => $project->id,
            'category_title' => $project->Category->title,
            'category_alias' => $project->Category->alias,
            'company_title' => $project->Company->title,
            'company_alias' => $project->Company->alias,
            'title' => $project->title,
            'target' => $project->target,
            'deadline' => $project->target,
            'content' => $project->content,
            'status' => $project->status,
        ];

        return response()->json($formatted, 200);
    }
}
