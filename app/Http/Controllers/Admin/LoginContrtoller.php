<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginContrtoller extends Controller
{
    //
 

    public function login(LoginRequest $request)
    {
        // Retrieve the user based on the email
        $user = User::where('email', $request->email)->first();
    
        // Check if the user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Log the user in
            Auth::login($user);
    
            // Check if the user has the 'admin' role
            if ($user->hasRole('admin')) {
                // Redirect admin to the admin dashboard
                return redirect()->intended('admin/dashboard')->with('success', 'Login successful!');
            } else {
                // If user is not an admin, log out and redirect back with an error message
                Auth::logout();
                return redirect()->back()->withErrors(['login' => 'You do not have permission to access this area.'])->withInput();
            }
        }
    
        // If authentication fails, redirect back with an error message
        return redirect()->back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }
}
