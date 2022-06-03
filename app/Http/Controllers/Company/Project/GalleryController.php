<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{

    public function create($alias, Request $request)
    {
        $user = $request->user();
        $project = Project::where('alias', $alias)->first();

        $validator = Validator::make($request->all(), [
            'file' => 'required|image',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $path = $request->file('file')->store(storage_path('app/public'));
        $user->Projects()->find($project->id)->Galleries()->create([
            'path' => $path
        ]);

        return response()->json(['error' => null]);
    }
}
