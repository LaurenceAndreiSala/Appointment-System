<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use Illuminate\Support\Facades\Storage;
use PDF;

class PrescriptionController extends Controller
{
public function downloadPrescription($id)
{
    $prescription = Prescription::with('appointment.doctor', 'appointment.patient')->findOrFail($id);
    $doctor = $prescription->appointment->doctor;

    // ✅ Handle doctor signature (stored in public/storage/signatures)
    $signatureData = null;
    $signatureMime = null;

    if (!empty($doctor->signature)) {
        // Ensure correct full path
        $signaturePath = public_path('storage/' . $doctor->signature);

        if (file_exists($signaturePath)) {
            $signatureData = base64_encode(file_get_contents($signaturePath));
            $signatureMime = mime_content_type($signaturePath);
        }
    }

    // ✅ Clinic logo (stored in public/img/clinic-logo.png)
    $logoData = null;
    $logoMime = null;
    $logoPath = public_path('img/clinic-logo.png');

    if (file_exists($logoPath)) {
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoMime = mime_content_type($logoPath);
    }

    // ✅ Generate PDF using the Blade view
    $pdf = PDF::loadView('patient.prescription_pdf', [
        'prescription'   => $prescription,
        'signatureData'  => $signatureData,
        'signatureMime'  => $signatureMime,
        'logoData'       => $logoData,
        'logoMime'       => $logoMime,
    ])->setPaper('A5', 'portrait');

    return $pdf->download('Prescription_' . $prescription->id . '.pdf');
}


    public function index($id)
    {
        return view('doctor.prescriptions.index', compact('id'));
    }

    public function view()
    {
        return view('patient.prescriptions');
    }
}
