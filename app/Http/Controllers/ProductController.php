<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function show()
    {
        $products = Products::all();
        return view('dashboard.index', compact('products'));
    }
    public function viewform()
    {
        $categories = Categories::all();
        //dd( $categories);
        return view('product.add', compact('categories'));
    }
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();

        return view('product.edit', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'required|image|max:2048'
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/products'), $imageName);

        Products::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('products')->with('success', 'Product added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $product = Products::findOrFail($id);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image) {
                $oldImagePath = public_path('images/products/' . $product->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        // Delete the image if it exists
        if ($product->image) {
            $imagePath = public_path('images/products/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Delete the product from the database
        $product->delete();

        return redirect()->route('products')->with('success', 'Product deleted successfully.');
    }
}
