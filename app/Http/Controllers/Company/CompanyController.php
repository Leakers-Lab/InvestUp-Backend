<?php

namespace App\Http\Controllers\Company;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $company = Company::with('Projects')->where('alias', $request['alias'])->first();

        if (!$company) {
            throw new NotFoundException('Company not found');
        }

        $projects = $company->Projects()->with(['Category'])->where('status', 'active')->get();

        $formatted = [];
        foreach ($projects as $project) {
            if ($project->Company->alias == $request['alias']) {
                $formatted[] = [
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
            }
        }
        $company['projects'] = $formatted;

        return response()->json($company);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required',
            'image' => 'required|image',
            'bg-image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        if (!empty($request->file('image'))) {
            $path = $request->file('image')->store('/', 'public');
            $validated['image'] = "http://server.investup.uz" .Storage::url($path);
        }

        if (!empty($request->file('bg-image'))) {
            $path1 = $request->file('bg-image')->store('/', 'public');
            $validated['bg-image'] = "http://server.investup.uz" .Storage::url($path1);
        }

        $alias = md5($validated['title'] . time());

        $validated['alias'] = $alias;

        $company = $user->Companies()->create($validated);

        return response()->json($company);
    }

    public function update(Request $request, $alias)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable',
            'image' => 'nullable|image',
            'bg-image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $validated = $validator->validated();

        if (!empty($request->file('image'))) {
            $path = $request->file('image')->store('/', 'public');
            $validated['image'] = "http://server.investup.uz" .Storage::url($path);
        }

        if (!empty($request->file('bg-image'))) {
            $path1 = $request->file('bg-image')->store('/', 'public');
            $validated['bg-image'] = "http://server.investup.uz" .Storage::url($path1);
        }

        $company = $user->Companies->where('alias', $alias)->first();
        $company->update($validated);

        return response()->json($company);
    }

    public function delete(Request $request, $alias)
    {
        $user = $request->user();

        $company = $user->Companies()->where('alias', $alias);
        $projects = $user->Companies()->where('alias', $alias)->first()->Projects()->delete();
        $company->delete();

        return response()->json(['error' => null]);
    }
}
