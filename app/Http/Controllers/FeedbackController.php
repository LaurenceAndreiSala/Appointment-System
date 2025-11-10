<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Appointment;

class FeedbackController extends Controller
{
    public function create($appointmentId)
    {
        return view('patient.feedback', compact('appointmentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string'
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        Feedback::create([
            'appointment_id' => $appointment->id,
            'patient_id' => auth()->id(),
            'doctor_id' => $appointment->doctor_id,
            'rating' => $request->rating,
            'comments' => $request->comments
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
