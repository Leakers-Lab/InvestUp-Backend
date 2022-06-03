<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $projects = Project::with(['Category', 'Company'])
                ->where('title', 'like', '%' . $request->search . '%')
                ->where('status', 'active')
                ->pagination(15);
        } else {
            $projects = Project::with(['Category', 'Company'])->where('status', 'active')->get();
        }

        foreach ($projects as $project) {
            $formatted[] = [
                'id' => $project->id,
                'category_title' => $project->Category->title,
                'category_alias' => $project->Category->alias,
                'company_title' => $project->Company->title,
                'company_alias' => $project->Company->alias,
                'title' => $project->title,
                'alias' => $project->alias,
                'target' => $project->target,
                'deadline' => $project->target,
                'content' => $project->content,
                'image' => $project->image,
                'status' => $project->status,
            ];
        }

        return response()->json($formatted);
    }

}
