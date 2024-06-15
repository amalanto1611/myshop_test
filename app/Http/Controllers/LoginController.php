<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('login.index');
    }
    public function dash()
    {
        return view('layout.app');
    }
    public function check(Request $request)
    {
        // Validate the incoming request data
        //dd($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');
        //dd( $credentials);

        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to the dashboard
            return redirect()->route('dashboard');
        } else {
            // Authentication failed, redirect back to the login with an error message
            return redirect()->route('login')->with('error', 'Invalid credentials. Please try again.');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
