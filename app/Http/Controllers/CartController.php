<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function viewCart(Request $request)
    {
        $cartItems = $request->session()->get('cart', []);

        // Retrieve product details from the database based on $cartItems
        $products = Products::whereIn('id', array_keys($cartItems))->get();

        // Associate each product with its quantity in the cart
            $cartProducts = $products->map(function ($product) use ($cartItems) {
            $product->quantity = $cartItems[$product->id];
            return $product;
        });

        // Get the user's mobile number from the session
        $userMobile = $request->session()->get('userMobile');

        return view('cart.index', [
            'cartProducts' => $cartProducts,
            'userMobile' => $userMobile, // Pass the user's mobile number to the view
        ]);
    }
    //function for adding cart 
    public function addToCart(Request $request, $productId)
    {
        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$productId])) {
            $cart[$productId] = 0;
        }

        $cart[$productId]++;

        $request->session()->put('cart', $cart);

        // Store the user's mobile number in the session
        $userMobile = Auth::user()->mobile_no;
        $request->session()->put('userMobile', $userMobile);

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function removeFromCart(Request $request, $productId)
    {
        // Remove the product from the session cart
        $cart = $request->session()->get('cart', []);

        // Check if the product exists in the cart
        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]); // Remove the product from the cart
            $request->session()->put('cart', $cart); // Update the cart in the session
        }

        return redirect()->back()->with('success', 'Product removed from cart successfully.');
    }

    public function sendWhatsapp($productId, $mobile)
    {
        //dd($mobile);
        // Ensure only admins can access this functionality
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Find the product information
        $product = Products::findOrFail($productId);

        // Construct the WhatsApp message link
        $whatsappLink = "https://wa.me/{$mobile}?text=Hello,%20you%20have%20the%20product%20{$product->name}%20in%20your%20cart.";

        // Redirect to the WhatsApp message link
        return redirect()->away($whatsappLink);
    }
}
