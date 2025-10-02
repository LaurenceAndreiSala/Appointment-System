<?php
// DoctorDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\AvailableSlot;
use App\Models\Notification;
use App\Models\Prescription;
use Illuminate\Support\Str;
use App\Events\CallStarted;
use Carbon\Carbon;
use Pusher\Pusher;


class DoctorDashboardController extends Controller
{
   public function index()
{
  // ✅ correct (for doctors)
$notificationCount = Appointment::where('doctor_id', auth()->id())
    ->whereIn('status', ['pending']) // only pending means "new"
    ->count();

$notifications = Appointment::with('patient')
    ->where('doctor_id', auth()->id())
    ->whereIn('status', ['pending'])
    ->orderBy('appointment_date', 'desc')
    ->take(10)
    ->get();

         $appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // only this doctor
    ->whereNotNull('patient_id')         // only if patient assigned
    ->get();

$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();

       $doctorId = auth()->id();

    $appointments = Appointment::with(['patient','doctor','slot'])
        ->where('doctor_id', $doctorId)
        ->whereNotNull('patient_id')
        ->get();

    $patients = User::where('role_id', 3)
        ->whereHas('appointments', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId)
              ->whereNotNull('patient_id');
        })
        ->get();

    $appointmentCount = $appointments->count();
    $patientCount = $patients->count();

    $prescriptionsCount = Prescription::whereHas('appointment', function ($q) use ($doctorId) {
        $q->where('doctor_id', $doctorId);
    })->count();

    // ✅ Pull notifications
    $notifData = $this->getDoctorNotifications();

    $appointmentCount = Appointment::where('doctor_id', auth()->id())
    ->whereNotNull('patient_id')
    ->count();

    $patientCount = User::where('role_id', 3)->count();

$appointments = Appointment::with(['patient','doctor','slot'])
    ->where('doctor_id', auth()->id())
    ->whereNotNull('patient_id')
    ->get();


$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();

        $prescriptionsCount = Prescription::whereHas('appointment', function ($q) {
    $q->where('doctor_id', auth()->id());   // only prescriptions written by this doctor
    })->count();



    return view('doctor.doctor-dashboard', array_merge ( compact('patientCount','appointments','appointmentCount','patients','notificationCount','notifications', 'prescriptionsCount')));
}

private function getDoctorNotifications()
{
    $doctorId = auth()->id();

    $notifications = Appointment::with('patient')
        ->where('doctor_id', $doctorId)
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();

    $notificationCount = Appointment::where('doctor_id', $doctorId)
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    return compact('notifications', 'notificationCount');
}


public function viewpatients()
{   
    $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $notifications = Appointment::with('patient')
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();

    $appointments = Appointment::with(['patient','doctor'])
        ->where('doctor_id', auth()->id())
        ->whereNotNull('patient_id')
        ->get();

$appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // ✅ only logged-in doctor
    ->whereNotNull('patient_id')
    ->get();

$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();


    return view('doctor.view-patients', compact('appointments','patients','notificationCount','notifications'));
}

public function writeprescripts()
{
    $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $notifications = Appointment::with('patient')
    ->orderBy('appointment_date', 'desc')
    ->take(10)
    ->get();
    
 $appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // ✅ only logged-in doctor
    ->whereNotNull('patient_id')
    ->get();

$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();


    $prescripts = Prescription::where('is_archived', 0)->get();


    $archivedPrescriptions = Prescription::where('is_archived', 1)->get();
    return view('doctor.write-prescriptions', compact('appointments','patients','notificationCount','notifications', 'archivedPrescriptions'));
}

public function storePrescription(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'medication'     => 'required|string|max:255',
        'dosage'         => 'required|string|max:255',
        'notes'          => 'nullable|string',
    ]);

    Prescription::create([
        'appointment_id' => $request->appointment_id,
        'medication'     => $request->medication,
        'dosage'         => $request->dosage,
        'notes'          => $request->notes,
    ]);

    return back()->with('success', 'Prescription saved successfully!');
}

 public function myprofile()
    {
        $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

        $notifications = Appointment::with('patient')
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();
       
        
        return view('doctor.my-profile', compact('notificationCount', 'notifications'));
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

public function archivePrescription(Request $request)
{
    $request->validate([
        'prescription_id' => 'required|exists:prescriptions,id'
    ]);

    $prescript = Prescription::findOrFail($request->prescription_id);
    $prescript->is_archived = 1;
    $prescript->save();

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => true]);
    }

    return back()->with('success', 'Prescription archived successfully!');
}

public function restorePrescription(Request $request)
{
    $prescript = Prescription::findOrFail($request->prescription_id);
    $prescript->is_archived = 0;
    $prescript->save();

    if ($request->ajax()) {
        // render row for Manage Prescriptions
        $rowHtml = view('doctor.partials.prescription_row', ['pres' => $prescript])->render();

        return response()->json(['success' => true, 'row_html' => $rowHtml]);
    }

    return back()->with('success', 'Prescription restored successfully!');
}

public function chatcall()
{
    $appointments = Appointment::with(['patient','doctor'])->get();

    $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $notifications = Appointment::with('patient')
    ->orderBy('appointment_date', 'desc')
    ->take(10)  
    ->get();

        return view('doctor.chat-call', compact('notificationCount','notifications','appointments'));
}

  public function viewappointment()
{
    $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $notifications = Appointment::with('patient')
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();

         $appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // only this doctor
    ->whereNotNull('patient_id')         // only if patient assigned
    ->get();

$appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())   // ✅ only logged-in doctor
    ->whereNotNull('patient_id')
    ->get();

$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();


    return view('doctor.view-appointment', compact('appointments','patients','notificationCount','notifications'));
}


public function approveAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'approved';
    $appointment->save();

    return back()->with('success', 'Appointment approved!');
}

public function denyAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'denied';
    $appointment->save();

    return back()->with('success', 'Appointment denied!');
}


public function setSlot()
{
    $slots = AvailableSlot::latest()->get();
    return view('admin.set-available-slots', compact('slots'));
}

public function storeSlot(Request $request)
{
    $request->validate([
        'doctor_id'   => 'required|exists:users,id',
        'date'        => 'required|date',
        'start_time'  => 'required',
        'end_time'    => 'required|after:start_time',
    ]);

    AvailableSlot::create([
        'doctor_id'    => $request->doctor_id, // ✅ comes from dropdown now
        'date'         => $request->date,
        'start_time'   => $request->start_time,
        'end_time'     => $request->end_time,
    ]);

    return redirect()->route('doctor.available-slots')->with('success', 'Slot added successfully!');
}


    public function viewAppointments()
{
    // Show all appointments for this doctor with patient info
    $appointments = Appointment::with('patient')
        ->where('doctor_id', auth()->id())
        ->orderBy('appointment_date', 'asc')
        ->orderBy('appointment_time', 'asc')
        ->get();

    return view('doctor.appointments', compact('appointments'));
}

public function fetchNotifications()
{
     $notifications = Notification::where('user_id', auth()->id())
        ->latest()
        ->take(10)
        ->get();

    $notifications = Appointment::with('patient')
        ->where('doctor_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function ($notif) {
            return [
                'id' => $notif->id,
                'status' => $notif->status,
                'appointment_date' => $notif->appointment_date,
                'appointment_time' => $notif->appointment_time,
                'patient' => [
                    'firstname' => $notif->patient->firstname ?? '',
                    'lastname' => $notif->patient->lastname ?? '',
                    'profile_picture' => $notif->patient->profile_picture 
                        ? asset($notif->patient->profile_picture) 
                        : asset('img/default-avatar.png'),
                ],
            ];
        });

    return response()->json([
        'count' => $notifications->count(),
        'notifications' => $notifications,
    ]);
}

public function markAsRead($id)
    {
       $notif = Notification::where('user_id', auth()->id())->findOrFail($id);
    $notif->is_read = true;
    $notif->save();

     
    return response()->json(['success' => true]);
}

public function markNotificationRead($id)
{
    $appointment = Appointment::where('doctor_id', auth()->id())
        ->where('id', $id)
        ->first();

    if (!$appointment) {
        return response()->json(['message' => 'Notification not found'], 404);
    }

    // Add `is_read` column in appointments table if not exists
    $appointment->is_read = true;
    $appointment->save();

    return response()->json(['message' => 'Notification marked as read']);
}

public function startCall($id, Request $request)
{
    try {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);

        if (!$appointment->patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Generate meeting URL if missing
        if (empty($appointment->meeting_url)) {
            $appointment->meeting_url = "https://meet.jit.si/appointment-" . $appointment->id;
            $appointment->save();
        }

        // Save notification
        Notification::create([
            'user_id' => $appointment->patient->id,
            'title'   => 'Incoming Call',
            'message' => 'Doctor is calling you for your appointment!',
            'is_read' => 0,
        ]);

        // ✅ Pass Appointment model instead of array
        broadcast(new \App\Events\CallStarted($appointment));

        return response()->json([
            'success' => true,
            'meeting_url' => $appointment->meeting_url,
        ]);

    } catch (\Throwable $e) {
        // Debug output
        \Log::error("StartCall Error: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error'   => $e->getMessage()
        ], 500);
    }
}
}

    // public function chatcall($id)
    // {
    //     $patient = User::findOrFail($id);
    //     return view('doctor.consultation', compact('patient'));
    // }