<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //

    public function showlogin(){

        return view('login');
    }


  public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // ✅ Role-based redirects
        if (Auth::user()->role_id == 1) { 
            return redirect()->route('admin.admin-dashboard'); 
        } elseif (Auth::user()->role_id == 2) { 
            return redirect()->route('doctor.doctor-dashboard'); 
        } else { 
            return redirect()->route('patient.patient-dashboard'); 
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}


    public function logout(Request $request): RedirectResponse{

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');

    }

}