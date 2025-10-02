<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showregister()
    {
        return view('auth.register'); // make sure you have resources/views/auth/register.blade.php
    }

    public function store(Request $request)
    {
        $request->validate([
        'firstname'   => 'required|string|max:255',
        'lastname'    => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email',
        'gender'      => 'required|string|max:255',
        'address'     => 'required|string|max:255',
        'contact_no'  => 'required|string|max:255',
        'birth_date' => 'required|date',
        'username'    => 'required|string|unique:users,username',
        'password'    => 'required|confirmed|min:6',
    ]);

    User::create([
        'firstname'  => $request->firstname,
        'lastname'   => $request->lastname,
        'email'      => $request->email,
        'gender'     => $request->gender,
        'address'    => $request->address,
        'birth_date' => $request->birth_date,
        'contact_no' => $request->contact_no,
        'username'   => $request->username,
        'password'   => Hash::make($request->password),
        'role_id'    => Role::where('name', 'patient')->first()->id,
        'status'     => 'pending',
    ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please login.');
    }
}
