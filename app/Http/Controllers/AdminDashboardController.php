<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\AvailableSlot;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SummaryExport;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $doctorCount = User::where('role_id', 2)->count();
        $patientCount = User::where('role_id', 3)->count();
        $patients = User::where('role_id', 3)->get();
        $doctors = User::where('role_id', 2)->get();
        $totaluserCount = User::whereIn('role_id', [2, 3])->count();
        $users = User::with('role')->get();

        $notificationCount = Appointment::whereIn('status', ['pending', 'approved'])->count();

       $totalPayments = Payment::where('payment_status', 'success')->sum('amount');


        // Monthly Payments
        $monthlyPayments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $janCount = $monthlyPayments[1] ?? 0;
        $febCount = $monthlyPayments[2] ?? 0;
        $marchCount = $monthlyPayments[3] ?? 0;
        $aprilCount = $monthlyPayments[4] ?? 0;
        $mayCount = $monthlyPayments[5] ?? 0;
        $juneCount = $monthlyPayments[6] ?? 0;
        $julyCount = $monthlyPayments[7] ?? 0;
        $augustCount = $monthlyPayments[8] ?? 0;
        $sepCount = $monthlyPayments[9] ?? 0;
        $octCount = $monthlyPayments[10] ?? 0;
        $novCount = $monthlyPayments[11] ?? 0;
        $decCount = $monthlyPayments[12] ?? 0;

        // Provide slots & archivedSlots as well so views that expect them won't break
        $slots = AvailableSlot::where('is_archived', 0)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $archivedSlots = AvailableSlot::where('is_archived', 1)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('admin.admin-dashboard', compact(
            'users',
            'doctorCount',
            'patientCount',
            'totaluserCount',
            'patients',
            'doctors',
            'totalPayments',
            'notificationCount',
            'janCount','febCount','marchCount','aprilCount','mayCount','juneCount',
            'julyCount','augustCount','sepCount','octCount','novCount','decCount',
            'slots', 'archivedSlots'
    )); 
}

public function setappointment()
{
     $slots = AvailableSlot::where('is_archived', 0)
        ->orderBy('date')
        ->orderBy('start_time')
        ->get();

    $archivedSlots = AvailableSlot::where('is_archived', 1)
        ->orderBy('date')
        ->orderBy('start_time')
        ->get();


    return view('admin.set-available-slots', compact('slots', 'archivedSlots'));
}


public function archiveSlot(Request $request)
{
    $slot = AvailableSlot::findOrFail($request->slot_id);
    $slot->is_archived = 1;
    $slot->save();

    return back()->with('success', 'Slot archived successfully!');
}

// Restore a slot
public function restoreSlot($id)
{
    $slot = AvailableSlot::findOrFail($id);
    $slot->is_archived = 0;
    $slot->save();

    return back()->with('success', 'Slot restored successfully!');
}

public function updatePatientInfo(Request $request)
{
    $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'height' => 'required|string|max:10',
        'weight' => 'required|string|max:10',
        'bmi' => 'nullable|string|max:10',
        'blood_type' => 'nullable|string|max:10',
        'advice' => 'nullable|string|max:500',
    ]);

    $appointment = Appointment::findOrFail($request->appointment_id);

    $appointment->height = $request->height;
    $appointment->weight = $request->weight;
    $appointment->bmi = $request->bmi;
    $appointment->blood_type = $request->blood_type;
    $appointment->advice = $request->advice;
    $appointment->save();

    return redirect()->back()->with('success', '✅ Patient information updated successfully!');
}


   public function viewallappointments()
{
    $appointments = Appointment::with(['patient','doctor','prescription'])->get();
    $patients = User::where('role_id', 3)->get(); // all patients
    $doctors = User::where('role_id', 2)->get();  
    $prescription = Prescription::all();
    return view('admin.view-appointment', compact('appointments','patients','prescription','doctors'));
}

public function storeSlot(Request $request)
{
    $request->validate([
        'doctor_id'   => 'required|exists:users,id',
        'sub_doctor_id' => 'nullable|exists:users,id|different:doctor_id',
        'date'        => 'required|date',
        'start_time'  => 'required|date_format:H:i',
        'end_time'    => 'required|date_format:H:i|after:start_time',
    ]);

    $doctor = User::findOrFail($request->doctor_id);

    $doctorId = $doctor->is_absent && $request->sub_doctor_id
        ? $request->sub_doctor_id
        : $request->doctor_id;

    AvailableSlot::create([
        'doctor_id'    => $doctorId,
        'sub_doctor_id'=> $request->sub_doctor_id,
        'date'         => Carbon::parse($request->date)->format('Y-m-d'),
        'start_time'   => Carbon::parse($request->start_time)->format('H:i:s'),
        'end_time'     => Carbon::parse($request->end_time)->format('H:i:s'),
    ]);

    return redirect()->route('admin.set-available-slots')->with('success', 'Slot added successfully!');
}

    public function settings()
{
        return view('admin.settings');
}

    public function summaryreport()
{
    $doctorCount = User::where('role_id', 2)->count();
    $patientCount = User::where('role_id', 3)->count();
    $patients = User::where('role_id', 3)->get();
    $doctors = User::where('role_id', 2)->get();
    $totaluserCount = User::whereIn('role_id', [2, 3])->count();
    $users = User::with('role')->get();

    // Notifications
    $notificationCount = Appointment::whereIn('status', ['pending', 'approved'])->count();

    // Appointments counts for summary cards / pie chart
    $completedAppointments = Appointment::where('status', 'approved')->count();
    $pendingAppointments   = Appointment::where('status', 'pending')->count();
    $cancelledAppointments = Appointment::where('status', 'cancelled')->count();

    // Financials: revenue/pending/refund (amounts)
    $revenue = (float) Payment::where('payment_status', 'success')->sum('amount');
    $pendingPayments = (float) Payment::where('payment_status', 'pending')->sum('amount');
    $refundPayments  = (float) Payment::where('payment_status', 'refund')->sum('amount');

    // Average per patient (guard division by zero)
    $patientsCount = max(1, $patientCount);
    $avgPerPatient = $patientsCount ? ($revenue / $patientsCount) : 0;

    // Monthly totals (array of 12 numbers, Jan..Dec)
    $monthlyPayments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $monthlyTotals = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyTotals[] = (float) ($monthlyPayments[$m] ?? 0);
    }

    // Appointments per day (last 7 days) - returns consistent labels even for zero days
    $start = Carbon::now()->subDays(6)->startOfDay();
    $end = Carbon::now()->endOfDay();

    $appts = Appointment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();

    $appointmentsPerDayLabels = [];
    $appointmentsPerDayData = [];
    for ($i = 6; $i >= 0; $i--) {
    $dateObj = Carbon::now()->subDays($i);
    $formatted = $dateObj->format('M d, Y'); 
    $dbFormat  = $dateObj->format('Y-m-d'); 

    $appointmentsPerDayLabels[] = $formatted;
    $appointmentsPerDayData[] = isset($appts[$dbFormat]) ? (int) $appts[$dbFormat] : 0;
}
             $totalPayments = Payment::where('payment_status', 'success')->sum('amount');

    // Slots (existing)
    $slots = AvailableSlot::where('is_archived', 0)
        ->orderBy('date')->orderBy('start_time')->get();

    $archivedSlots = AvailableSlot::where('is_archived', 1)
        ->orderBy('date')->orderBy('start_time')->get();

    return view('admin.summary-report', compact(
        'users','doctorCount','patientCount','totaluserCount','totalPayments',
        'patients','doctors','notificationCount',
        'completedAppointments','pendingAppointments','cancelledAppointments',
        'revenue','pendingPayments','refundPayments','avgPerPatient',
        'monthlyTotals','appointmentsPerDayLabels','appointmentsPerDayData',
        'slots','archivedSlots'
    ));
}

public function approveAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'confirmed'; // ✅ valid value
    $appointment->save();

    return redirect()->back()->with('success', 'Appointment confirmed!');
}

public function denyAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'cancelled'; // ✅ valid value
    $appointment->save();

    return redirect()->back()->with('success', 'Appointment cancelled!');
}

public function exportPDF()
{
      // ✅ Appointments per day (last 7 days)
    $appointmentsPerDay = Appointment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->pluck('count', 'date');

    // ✅ Financial Overview
    $revenue = Payment::where('payment_status', 'success')->sum('amount');
    $pending = Payment::where('payment_status', 'pending')->sum('amount');
    $refund  = Payment::where('payment_status', 'refund')->sum('amount');
    $patientCount = User::where('role_id', 3)->count();
    $patientsCount = max(1, $patientCount);
    $avgPerPatient = $patientsCount ? ($revenue / $patientsCount) : 0;
    $completedAppointments = Appointment::where('status', 'approved')->count();
    $cancelledAppointments = Appointment::where('status', 'cancelled')->count();
    $pendingAppointments   = Appointment::where('status', 'pending')->count();
    $totalPayments         = Payment::where('payment_status', 'success')->sum('amount');

    $pdf = Pdf::loadView('admin.reports.summary-pdf', compact(
        'completedAppointments','cancelledAppointments',
        'pendingAppointments','totalPayments','appointmentsPerDay',
        'revenue','pending','refund','avgPerPatient','patientsCount','patientCount'
    ));
    return $pdf->download('summary-report.pdf');
}

public function exportExcel()
{
    return Excel::download(new SummaryExport, 'summary-report.xlsx');
}

public function toggleAbsence(Request $request, User $doctor)
{
    // Toggle absence flag
    $doctor->is_absent = !$doctor->is_absent;
    $doctor->save();

    if ($doctor->is_absent) {
        // ✅ When doctor is absent, assign sub doctor to available_slots.sub_doctor_id
        $slots = AvailableSlot::where('doctor_id', $doctor->id)
            ->whereDate('date', '>=', now()->toDateString()) // only future slots
            ->get();

        foreach ($slots as $slot) {
            // only update if sub_doctor_id is still NULL
            if (is_null($slot->sub_doctor_id)) {
                // Example: pick the first available doctor with role_id = 2 (sub doctor)
                $subDoctor = User::where('role_id', 2)
                                 ->where('id', '!=', $doctor->id) // not the same doctor
                                 ->first();

                if ($subDoctor) {
                    $slot->sub_doctor_id = $subDoctor->id;
                    $slot->save();
                }
            }
        }
    } else {
        // ✅ If doctor comes back, clear sub doctor from slots
        AvailableSlot::where('doctor_id', $doctor->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->update(['sub_doctor_id' => null]);
    }

    return back()->with('success', 'Doctor absence status updated and slots reassigned.');
}

}