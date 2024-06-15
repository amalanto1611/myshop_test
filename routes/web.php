<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustlogController;
use App\Http\Controllers\Web\CategoryController;




Route::group(['middleware' => 'auth'], function () {

    //dashboad view 
    Route::get('/dashboard', [LoginController::class, 'dash'])->name('dashboard');
    
    //products managing routes
    Route::get('/products', [ProductController::class, 'show'])->name('products');
    Route::get('/products/addform', [ProductController::class, 'viewform'])->name('product.addview');
    Route::post('/products/createproduct', [ProductController::class, 'store'])->name('product.create');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    //for cart management 
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::delete('/cart/{productId}/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    //routes for sending whats app
    Route::get('/cart/send-whatsapp/{productId}/{mobile}', [CartController::class, 'sendWhatsapp'])->name('cart.sendWhatsapp');
    
    //for setting up Api integration in categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.list');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

});
//user registaration 
Route::get('/register', [CustlogController::class, 'showRegistrationForm'])->name('register');
Route::post('/logincheck', [Logincontroller::class, 'check'])->name('login.check');
Route::post('/register/signup', [CustlogController::class, 'register'])->name('registersignup');
//for log out
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
//index login page
Route::get('/', [LoginController::class, 'show'])->name('login');