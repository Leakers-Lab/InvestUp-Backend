<?php

namespace App\Http\Controllers\Company\Project;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $path = $request->file('file')->store('/', 'public');
        $user->Companies->find($project->company_id)->Projects()->find($project->id)->Galleries()->create([
            'path' => "https://server.investup.uz" .Storage::url($path)
        ]);

        return response()->json(['path' => "https://server.investup.uz" .Storage::url($path)]);
    }
}
