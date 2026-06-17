<?php

namespace App\Http\Controllers\Admin;

use App\Events\LoginHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function loginPage()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:4'
        ]);
    
        $loginField = filter_var($request->input('email_or_phone'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $loginField => $request->input('email_or_phone'),
            'password' => $request->input('password')
        ];
    
        // login attempt if successful then redirect home
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
    
            // Event Fire/Trigger/Dispatch
            $user = Auth::user();
            event(new LoginHistory($user));
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->previous()]);
            }
    
            return redirect()->route('admin.dashboard');
        }
    
        // return error message
        return back()->withErrors([
            'email_or_phone' => 'Wrong Credentials found!'
        ])->onlyInput('email_or_phone');
    }
    

    public function adminLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
