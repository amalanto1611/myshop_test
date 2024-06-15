<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Products;

class CategoryController extends Controller
{
    public function index()
    {
          $categories = Categories::all();
        //dd( $categories);
        return response()->json($categories);
    }
    public function productindex(Request $request)
    {
        //dd($request);
        $category_id = $request->query('category_id');
        $products = Products::where('category_id', $category_id)->get();

        return response()->json($products);
    }
    public function show($id)
    {
        
        try {
            // Fetch the category by ID from the database
            $categories = Categories::findOrFail($id);
            //dd( $categories);
            return response()->json($categories);
           
        } catch (\Exception $e) {
            // Handle exceptions (e.g., category not found)
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404); // Return 404 Not Found status code
        }
    }

}
