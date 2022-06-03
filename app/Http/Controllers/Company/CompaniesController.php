<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::all();

        return response()->json($companies);
    }
}
