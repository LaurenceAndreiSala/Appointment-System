<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showregister()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'gender'      => 'required|string|max:255',
            'birth_date'  => 'required|date',
            'age'         => 'required|integer|min:1',
            'contact_no'  => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'password'    => 'required|confirmed|min:6',
        ]);

        // ✅ Create the user account
        $patientRole = Role::where('name', 'patient')->first();

        $user = User::create([
            'firstname'  => $request->firstname,
            'lastname'   => $request->lastname,
            'email'      => $request->email,
            'gender'     => $request->gender,
            'birth_date' => $request->birth_date,
            'age'        => $request->age,
            'address'    => $request->address,
            'contact_no' => $request->contact_no,
            'password'   => Hash::make($request->password),
            'role_id'    => $patientRole ? $patientRole->id : 3, // fallback role_id=3
            'status'     => 'active',
        ]);

        // ✅ Automatically log the user in
        Auth::login($user);

        // ✅ Redirect to patient dashboard
        return redirect()->route('patient.patient-dashboard')
                         ->with('success', 'Welcome to MediCAL! Your account has been created.');
    }
}
