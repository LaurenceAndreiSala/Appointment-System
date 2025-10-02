<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class DoctorController extends Controller
{
    public function create()
    {
        $doctors = User::where('role_id', 2)->get();
        return view('admin.create-doctors', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'    => 'required|string|in:active,inactive',
        ]);

        $fileName = null;
        if ($request->hasFile('profile_picture')) {
            $fileName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profile'), $fileName);
        }

        User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => $request->status,
            'contact_no' => $request->contact_no,
            'profile_picture' => $fileName ? 'uploads/profile/' . $fileName : null,
            'address'    => $request->address,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'role_id'   => Role::where('name', 'doctor')->first()->id,
        ]);

        return redirect()->back()->with('success', 'Doctor account created successfully.');
    }

    public function updateProfile(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update doctor info
        $doctor->firstname = $request->firstname;
        $doctor->lastname = $request->lastname;
        $doctor->email = $request->email;

        // Update password only if provided
        if ($request->filled('password')) {
            $doctor->password = Hash::make($request->password);
        }

        // Upload new profile picture if available
        if ($request->hasFile('profile_picture')) {
            $fileName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profile'), $fileName);
            $doctor->profile_picture = 'uploads/profile/' . $fileName;
        }

        $doctor->save();

        return redirect()->back()->with('success', 'Doctor profile updated successfully!');
    }

    public function destroy($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')
                         ->with('success', 'Doctor deleted successfully!');
    }
}
