<?php

namespace App\Http\Controllers\Company\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->get();

        return response()->json($categories);
    }
}
