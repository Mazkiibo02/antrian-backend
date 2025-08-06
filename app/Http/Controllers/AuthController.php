<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => '/admin/dashboard']);
            }
            
            return redirect()->intended('/admin/dashboard');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'errors' => ['email' => ['Email atau password salah.']]
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect('/admin/login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
} 