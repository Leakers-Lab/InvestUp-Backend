<?php

namespace App\Http\Controllers\Company;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $company = Company::with('Projects')->where('alias', $request['alias'])->first();

        if (!$company) {
            throw new NotFoundException('Company not found');
        }

        $projects = $company->Projects()->with(['Category'])->where('status', 'active')->get();

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

        return response()->json($company, 200);
    }
}
