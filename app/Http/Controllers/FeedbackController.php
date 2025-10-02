<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create($appointmentId) { return view('patient.feedback', compact('appointmentId')); }
    public function store(Request $request) { /* save feedback */ }
}
