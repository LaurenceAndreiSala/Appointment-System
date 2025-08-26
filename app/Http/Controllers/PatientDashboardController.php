<?php

// PatientDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PatientDashboardController extends Controller
{
    public function index()
    {
        return view('patient.patient-dashboard');
    }
    public function showbook()
    {
        return view('patient.book-appointment');
    }
    public function viewappointment()
    {
        return view('patient.view-appointment');
    }
    public function chatcall()
    {
        return view('patient.video-call');
    }
    public function feedback()
    {
        return view('patient.give-feedback');
    }
    public function precription()
    {
        return view('patient.view-precription');
    }

    public function approve($id)
{
    $patient = User::findOrFail($id);
    $patient->status = 'approved';
    $patient->save();

    return redirect()->back()->with('success', 'Patient approved successfully.');
}

public function deny($id)
{
    $patient = User::findOrFail($id);
    $patient->status = 'denied';
    $patient->save();

    return redirect()->back()->with('success', 'Patient denied.');
}

}