<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'image' => $project->image,
            'status' => $project->status,
            'comments' => $project->Comments,
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
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        $alias = md5($validated['title'] . time());

        $validated['alias'] = $alias;

        $path = $request->file('image')->store('/', 'public');

        if (!empty($request->file('image'))) {
            $validated['image'] = Storage::url($path);
        }

        $company = $user->Companies()->find($validated['company_id'])->Projects()->create($validated);

        return response()->json(['error' => null]);
    }

    public function update(Request $request, $alias)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'title' => 'nullable|string',
            'target' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'content' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        if (!empty($request->file('image'))) {
            $path = $request->file('image')->store('/', 'public');
            $validated['image'] = Storage::url($path);
        }

        $project = Project::where('alias', $alias)->first();
        $project->update($validated);

        return response()->json($project);
    }
}
