<?php

// PatientDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientDashboardController extends Controller
{
    public function index()
    {
        return view('patient.patient-dashboard');
    }
}
