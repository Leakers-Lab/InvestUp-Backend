<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $projects = Project::with(['Category', 'Company'])
                ->where('title', 'like', '%' . $request->search . '%')
                ->where('status', 'active')
                ->get();
        } else {
            $projects = Project::with(['Category', 'Company'])->where('status', 'active')->get();
        }

        $formatted = [];
        foreach ($projects as $project) {
            $collected = DB::select("SELECT SUM(amount) as total FROM donations WHERE project_id = {$project->id}")[0]->total;
            $formatted[] = [
                'id' => $project->id,
                'category_title' => $project->Category->title,
                'category_alias' => $project->Category->alias,
                'company_title' => $project->Company->title,
                'company_alias' => $project->Company->alias,
                'title' => $project->title,
                'description' => $project->description,
                'alias' => $project->alias,
                'target' => $project->target,
                'deadline' => $project->deadline,
                'content' => $project->content,
                'image' => $project->image,
                'status' => $project->status,
                'collected' => $collected ?? 0
            ];
        }

        return response()->json($formatted);
    }

}
