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

        return view('auth.login');
    }


  public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $roleId = Auth::user()->role_id;

        switch ($roleId) {
            case 1:
                return redirect()->route('admin.admin-dashboard'); 
            case 2:
                return redirect()->route('doctor.doctor-dashboard'); 
            case 3:
                return redirect()->route('patient.patient-dashboard'); 
            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Unauthorized role']);
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