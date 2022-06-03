<?php

namespace App\Http\Controllers\User\Comments;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function create($alias, Request $request)
    {
        $user = $request->user();
        $project = Project::where('alias', $alias)->first();

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();
        $validated['user_id'] = $user->id;

        $project->Comments()->create($validated);

        return response()->json(['error' => null]);
    }

    public function index($alias)
    {
        $project = Project::with('Comments.User')->where('alias', $alias)->first();

        return response()->json($project->Comments);
    }
}
