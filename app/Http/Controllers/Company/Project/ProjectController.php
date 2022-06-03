<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index($alias)
    {
        $project = Project::with(['Category', 'Company', 'Comments.User', 'Galleries'])->where('alias', $alias)->where('status', 'active')->first();

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
            'comments' => $project->Comments,
            'gallery' => $project->Galleries,
        ];

        return response()->json($formatted);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'company_id' => 'required|integer',
            'title' => 'required|string',
            'target' => 'required|integer',
            'deadline' => 'required|date',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $alias = md5($validated['title'] . time());

        $company = $user->Companies()->find($validated['company_id'])->Projects()->create([
            'category_id' => $validated['category_id'],
            'company_id' => $validated['category_id'],
            'title' => $validated['title'],
            'alias' => $alias,
            'target' => $validated['target'],
            'deadline' => $validated['deadline'],
            'content' => $validated['content'],
        ]);

        return response()->json(['error' => null]);
    }

    public function update($alias, Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'company_id' => 'required|integer',
            'title' => 'required|string',
            'target' => 'required|integer',
            'deadline' => 'required|date',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $project = Project::where('alias', $alias);
        $project->update($validated);

        return response()->json($project);
    }
}
