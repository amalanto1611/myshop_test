<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function index()
    {
        $response = Http::get(url('/api/categories'));
        //dd($response); // Check the HTTP status code
        if ($response->successful()) {
            $categories = $response->json();
        } else {
            $categories = [];
        }

        return view('category.index', compact('categories'));
    }
    public function show($id)
    {
        //dd($id);
        $categoryResponse = Http::get(url('/api/categories/' . $id));
        $productsResponse = Http::get(url('/api/products'), ['category_id' => $id]);
        //dd( $productsResponse);
        $category = $categoryResponse->json();
        $products = $productsResponse->json();
        
         //dd($category);
        return view('category.show', compact('category', 'products'));
    }
}
