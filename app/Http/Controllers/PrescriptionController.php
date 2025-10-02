<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index($id) { return view('doctor.prescriptions.index', compact('id')); }
    public function store(Request $request) { /* save prescription */ }

    public function view() { return view('patient.prescriptions'); }
}
