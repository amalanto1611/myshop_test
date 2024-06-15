<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }
    public function productindex(Request $request)
    {
        $category_id = $request->query('category_id');
        $products = Product::where('category_id', $category_id)->get();

        return response()->json($products);
    }
}
