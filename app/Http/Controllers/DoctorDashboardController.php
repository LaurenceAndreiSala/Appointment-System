<?php
// DoctorDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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

    $doctors = User::where('role_id', 2)->get();
    $appointments = Appointment::with(['patient','doctor'])
    ->where('doctor_id', auth()->id())  
    ->whereNotNull('patient_id')
    ->orderBy('appointment_date', 'asc')
    ->orderBy('appointment_time', 'asc')
    ->get();

$patients = User::where('role_id', 3)
    ->whereHas('appointments', function ($q) {
        $q->where('doctor_id', auth()->id())
          ->whereNotNull('patient_id');
    })
    ->get();


    $prescripts = Prescription::where('is_archived', 0)->get();


    $archivedPrescriptions = Prescription::where('is_archived', 1)->get();
    return view('doctor.write-prescriptions', compact('appointments','patients','doctors','notificationCount','notifications', 'archivedPrescriptions'));
}

public function storePrescription(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'medication'     => 'required|string|max:255',
        'dosage'         => 'required|string|max:255',
        'notes'          => 'nullable|string',
    ]);

    $doctor = auth()->user();

    // Get doctor's saved signature path (if any)
    $signaturePath = $doctor->signature ? $doctor->signature : null;

    Prescription::create([
        'appointment_id' => $request->appointment_id,
        'medication'     => $request->medication,
        'dosage'         => $request->dosage,
        'notes'          => $request->notes,
        'signature_path' => $signaturePath, // ✅ store doctor signature path
    ]);

    return back()->with('success', 'Prescription saved with electronic signature!');
}

public function myprofile()
    {
        $doctor = auth()->user()->doctor; // adjust if your auth relationship differs
        $notificationCount = Appointment::where('patient_id', auth()->id())
        ->whereIn('status', ['pending', 'approved'])
        ->count();
       
        $notifications = Appointment::with('patient')
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();
       
        
        return view('doctor.my-profile', compact('notificationCount', 'notifications','doctor'));
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

  public function viewappointment(Request $request)
{
    $status = $request->query('status'); // get status filter from query string, e.g., ?status=pending

    $doctorId = auth()->id();

    $appointmentsQuery = Appointment::with(['patient', 'doctor'])
        ->where('doctor_id', $doctorId)
        ->whereNotNull('patient_id');

    if ($status && in_array($status, ['pending', 'approved', 'denied', 'cancelled'])) {
        $appointmentsQuery->where('status', $status);
    }

    // Optional: sort by status order (Pending → Approved → Denied → Cancelled)
    $statusOrder = ['pending', 'approved', 'denied', 'cancelled'];
    $appointmentsQuery->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "')");

    $appointments = $appointmentsQuery->get();

    $patients = User::where('role_id', 3)
        ->whereHas('appointments', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId)
              ->whereNotNull('patient_id');
        })
        ->get();

    $notificationCount = Appointment::where('patient_id', $doctorId)
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $notifications = Appointment::with('patient')
        ->orderBy('appointment_date', 'desc')
        ->take(10)
        ->get();

    return view('doctor.view-appointment', compact('appointments', 'patients', 'notificationCount', 'notifications'));
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
    // Get first two doctors
    $doctors = User::where('role_id', 2)->orderBy('id')->take(2)->get();
    $mainDoctor = $doctors->first();
    $subDoctor  = $doctors->skip(1)->first();

    // Automatically assign doctor
    $assignedDoctorId = $mainDoctor->is_absent && $subDoctor
                        ? $subDoctor->id
                        : $mainDoctor->id;

    AvailableSlot::create([
        'doctor_id'    => $assignedDoctorId,
        'sub_doctor_id'=> $subDoctor ? $subDoctor->id : null,
        'date'         => Carbon::parse($request->date)->format('Y-m-d'),
        'start_time'   => Carbon::parse($request->start_time)->format('H:i:s'),
        'end_time'     => Carbon::parse($request->end_time)->format('H:i:s'),
    ]);

    return redirect()->route('admin.set-available-slots')
                     ->with('success', 'Slot added successfully!');
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
        ->whereDate('created_at', Carbon::today()) // today only
        ->latest()
        ->take(10)
        ->get();

     $today = \Carbon\Carbon::today();

    $notifications = Appointment::with('patient')
        ->where('doctor_id', auth()->id())
        ->whereDate('appointment_date', $today) // ✅ only today
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('appointment_date', 'asc')
        ->take(10)
        ->get()
        ->map(function ($appt) {
            return [
                'id' => $appt->id,
                'appointment_date' => \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y'),
                'appointment_time' => $appt->slot ? \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') : 'N/A',
                'patient' => [
                    'firstname' => $appt->patient->firstname ?? '',
                    'lastname'  => $appt->patient->lastname ?? '',
                    'profile_picture' => $appt->patient->profile_picture 
                        ? asset($appt->patient->profile_picture) 
                        : asset('img/default-avatar.png'),
                ],
            ];
        });

  return response()->json([
        'count' => $notifications->where('is_read', 0)->count(),
        'notifications' => $notifications->map(function($notif) {
            return [
                'id' => $notif->id,
                'title' => $notif->title,
                'message' => $notif->message,
                'is_read' => $notif->is_read,
                'created_at' => $notif->created_at->format('M d, Y h:i A'),
            ];
        }),
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
            return response()->json(['success' => false, 'error' => 'Patient not found'], 404);
        }

        // Generate meeting URL if missing
        if (empty($appointment->meeting_url)) {
            $appointment->meeting_url = "https://meet.jit.si/appointment-" . $appointment->id;
            $appointment->save();
        }

        // Save notification with meeting URL in the 'data' field
        Notification::create([
            'user_id' => $appointment->patient->id,
            'title'   => 'Incoming Call',
            'message' => 'Doctor is calling you for your appointment!',
            'data'    => json_encode(['meeting_url' => $appointment->meeting_url]),
            'is_read' => 0,
        ]);

        // Broadcast the call event
        broadcast(new \App\Events\CallStarted($appointment));

        return response()->json([
            'success' => true,
            'meeting_url' => $appointment->meeting_url,
        ]);

    } catch (\Throwable $e) {
        \Log::error("StartCall Error: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function editSignature()
{
    $doctor = auth()->user(); // ✅ direct user record
    return view('doctor.signature_edit', compact('doctor'));
}

public function updateSignature(Request $request)
{
    $request->validate([
          'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    $doctor = auth()->user(); // ✅ direct user record

    // delete old signature if exists
    if ($doctor->signature && Storage::disk('public')->exists($doctor->signature)) {
        Storage::disk('public')->delete($doctor->signature);
    }

    // upload new
    $path = $request->file('signature')->store('signatures', 'public');
    $doctor->signature = $path;
    $doctor->save();

    return back()->with('success', 'Signature uploaded.');
}

}
    // public function chatcall($id)
    // {
    //     $patient = User::findOrFail($id);
    //     return view('doctor.consultation', compact('patient'));
    // }