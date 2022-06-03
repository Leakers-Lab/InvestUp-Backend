<?php

namespace App\Http\Controllers\Company\Category;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($alias)
    {
        $category = Category::with('Projects')->where('alias', $alias)->where('status', 'active')->first();

        if (!$category) {
            throw new NotFoundException('Category not found');
        }

        return response()->json($category);
    }
}
