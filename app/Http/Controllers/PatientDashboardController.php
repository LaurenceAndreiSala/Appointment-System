<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AvailableSlot;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Notification;
use Illuminate\Support\Str;
use App\Models\Feedback;
use App\Events\AppointmentBooked;

use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    // ✅ Dashboard with patient’s appointments
public function index()
{
    
    $appointments = Appointment::with('doctor')
        ->where('patient_id', auth()->id())
        ->latest()
        ->take(5) // show latest 5
        ->get();

    $prescriptions = Prescription::with(['appointment.doctor'])
        ->whereHas('appointment', function ($q) {
            $q->where('patient_id', auth()->id());
        })
        ->latest()
        ->take(5) // show latest 5
        ->get();

        $patientId = Auth::id();

    $appointments = Appointment::with('doctor')
        ->where('patient_id', $patientId)
        ->orderBy('appointment_date', 'asc')
        ->take(5)
        ->get();
        
    $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'complete'])
        ->count();

    $doctorCount = User::where('role_id', 2)->count();

    return view('patient.patient-dashboard', compact('appointments', 'prescriptions','notificationCount','doctorCount'));
}


public function notifications()
{
    $patientId = Auth::id();

    // Get today's appointments only
    $appointments = Appointment::with(['doctor', 'slot'])
        ->where('patient_id', $patientId)
        ->whereDate('appointment_date', Carbon::today())
        ->orderBy('appointment_date', 'asc')
        ->get();

    return response()->json([
        'count' => $appointments->count(),
        'notifications' => $appointments->map(function ($appt) {
            $formattedDate = Carbon::parse($appt->appointment_date)->format('F j, Y');
            $startTime = Carbon::parse($appt->slot->start_time)->format('g:i A');
            $endTime   = Carbon::parse($appt->slot->end_time)->format('g:i A');

            return [
                'id' => $appt->id,
                'message' => "Your appointment with Dr. " .
                             $appt->doctor->firstname . " " . $appt->doctor->lastname .
                             " today ({$formattedDate}) from {$startTime} - {$endTime} is " . ucfirst($appt->status) . "."
            ];
        }),
    ]);
}


    // ✅ Book appointment page (list doctors + slots)
public function showbook()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in to book an appointment.');
    }

    // ✅ Fetch doctors but hide absent ones, show sub doctor instead
    $doctors = User::where('role_id', 2)
        ->get()
        ->map(function ($doctor) {
            if ($doctor->is_absent && $doctor->subDoctor) {
                // Replace absent doctor with sub doctor
                return $doctor->subDoctor;
            }
            return $doctor;
        });

   $slots = AvailableSlot::with(['doctor', 'subDoctor'])
    ->orderBy('date')
    ->orderBy('start_time')
    ->get()
    ->map(function ($slot) {
        if ($slot->doctor && $slot->doctor->is_absent && $slot->subDoctor) {
            $slot->assigned_doctor = $slot->subDoctor; // dynamically replace
        } else {
            $slot->assigned_doctor = $slot->doctor;
        }
        return $slot;
    });


    return view('patient.book-appointment', compact('doctors', 'slots'));
}



    // ✅ View my appointments
    public function viewappointment()
    {
        $appointments = Appointment::where('patient_id', auth()->id())->get();
        return view('patient.view-appointment', compact('appointments'));
    }

   public function myprofile()
    {
        return view('patient.my-profile');
    }

    // ✅ Video call page
public function chatcalls()
{
             $appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // only this doctor
    ->whereNotNull('patient_id')         // only if patient assigned
    ->get();

    $appointments = Appointment::with(['doctor', 'slot'])
        ->where('patient_id', auth()->id())
        ->where('status', 'complete') // only complete
        ->whereNotNull('meeting_url') // must have link
        ->orderBy('appointment_date', 'asc')
        ->get();

    return view('patient.video-call', compact('appointments'));
}

    // ✅ Feedback page
// ✅ Show feedback form for a completed appointment
public function feedback($appointmentId)
{
    $appointment = Appointment::where('id', $appointmentId)
        ->where('patient_id', auth()->id())
        ->where('status', 'complete') // only completed appointments
        ->firstOrFail();

    // Check if feedback already exists
    $existingFeedback = $appointment->feedback;

    return view('patient.give-feedback', compact('appointment', 'existingFeedback'));
}

public function storeFeedback(Request $request, $appointmentId)
{
    $appointment = Appointment::where('id', $appointmentId)
        ->where('patient_id', auth()->id())
        ->where('status', 'complete')
        ->firstOrFail();

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // Create or update feedback
    $appointment->feedback()->updateOrCreate(
        ['appointment_id' => $appointment->id],
        [
            'patient_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return redirect()->route('patient.view-appointment')
                     ->with('success', '✅ Feedback submitted successfully!');
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'contact_no'  => 'required|string|max:255',
        'email'     => 'required|email|unique:users,email,' . $user->id,
        'password'  => 'nullable|min:6|confirmed',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update basic info
    $user->firstname = $request->firstname;
    $user->lastname  = $request->lastname;
    $user->contact_no  = $request->contact_no;
    $user->email     = $request->email;

    // Update password only if provided
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Upload profile picture
    if ($request->hasFile('profile_picture')) {
        $fileName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->move(public_path('uploads/profile'), $fileName);
        $user->profile_picture = 'uploads/profile/' . $fileName;
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
}

    // ✅ My prescriptions (linked to appointments)
    public function prescriptions()
    {
        $prescriptions = Prescription::with(['appointment.doctor'])
            ->whereHas('appointment', function ($q) {
                $q->where('patient_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('patient.view-prescriptions', compact('prescriptions'));
    }

    // ✅ Cancel my appointment
    public function cancel($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment cancelled successfully!');
    }

   // ✅ Book appointment (store in DB)
public function store(Request $request)
{
    $request->validate([
        'doctor_id' => 'required|exists:users,id',
        'slot_id'   => 'required|exists:available_slots,id',
    ]);

    $slot = AvailableSlot::with('doctor', 'subDoctor')->findOrFail($request->slot_id);

   // ✅ Create unique meeting URL (example Jitsi)
$meetingUrl = "https://meet.jit.si/appointment-" . uniqid();

    if ($slot->is_taken) {
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'This slot is already taken.'])
            : redirect()->back()->with('error', 'This slot is already taken.');
    }

    // ✅ Check if main doctor is absent
    $doctorId = $slot->doctor && $slot->doctor->is_absent
        ? $slot->sub_doctor_id
        : $slot->doctor_id;

 $appointment = Appointment::create([
    'slot_id'          => $slot->id,
    'doctor_id'        => $doctorId,
    'patient_id'       => auth()->id(),
    'status'           => 'pending',
    'appointment_date' => $slot->date,
    'meeting_url'      => $meetingUrl,
    'appointment_time' => $slot->start_time,
]);

// ✅ Create notification for doctor
Notification::create([
    'user_id' => $doctorId,
    'title' => 'New Appointment Today',
    'message' => "New appointment with " . auth()->user()->firstname . " " . auth()->user()->lastname .
                 " on " . $slot->date . " at " . $slot->start_time,
    'is_read' => 0,
]);


// Fire real-time event
event(new AppointmentBooked($appointment));

    $slot->update(['is_taken' => true]);

    return redirect()->route('patient.book-appointment')
                     ->with('success', '✅ Your appointment has been successfully booked!');
}


public function getSlots($year, $month)
{
    $slots = \App\Models\AvailableSlot::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get();

    return response()->json([
        'slots' => $slots
    ]);
}

public function renderCalendar($year, $month)
{
    $slots = \App\Models\AvailableSlot::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get();

    return view('partials.calendar', [
        'slots' => $slots,
        'year' => $year,
        'month' => $month
    ])->render();
}

public function getDaySlots($date)
{
    $slots = \App\Models\AvailableSlot::whereDate('date', $date)->get();

    return view('partials.day-slots', [
        'slots' => $slots,
        'date' => $date
    ])->render();
}

public function join($uuid)
{
    $appointment = Appointment::where('meeting_url', url('/meeting/' . $uuid))->firstOrFail();

    // build the Jitsi link dynamically
    $jitsiUrl = "https://meet.jit.si/appointment-" . $appointment->id;

    return view('meeting.room', compact('appointment', 'jitsiUrl'));
}

}
