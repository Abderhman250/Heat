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
            $redirect = [
                'admin' => 'admin/dashboard',
                'office_manager' => 'admin/booking'
            ];
            
            if ($user->hasRole(['admin', 'office_manager'])) {

                $role = $user->hasRole('admin') ? 'admin' : 'office_manager';

                return redirect()->intended($redirect[$role] ?? 'admin/dashboard')
                                 ->with('success', 'Login successful!');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['login' => 'You do not have permission to access this area.'])->withInput();
            }
        }

        // If authentication fails, redirect back with an error message
        return redirect()->back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin/login')->with('status', 'You have been successfully logged out!');
    }
}
