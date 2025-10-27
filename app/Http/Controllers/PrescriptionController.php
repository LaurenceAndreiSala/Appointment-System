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

    // ✅ Doctor Signature (public/storage/signatures/)
    $signatureData = null;
    $signatureMime = null;
    if (!empty($doctor->signature)) {
        $signaturePath = public_path('storage/' . $doctor->signature);
        if (file_exists($signaturePath)) {
            $signatureData = base64_encode(file_get_contents($signaturePath));
            $signatureMime = mime_content_type($signaturePath);
        }
    }

    // ✅ Clinic Logo (public/img/clinic-logo.png)
    $clinicLogoData = null;
    $clinicLogoMime = null;
    $clinicLogoPath = public_path('img/clinic-logo.png');
    if (file_exists($clinicLogoPath)) {
        $clinicLogoData = base64_encode(file_get_contents($clinicLogoPath));
        $clinicLogoMime = mime_content_type($clinicLogoPath);
    }

    // ✅ RX Icon (public/img/rx-icons.png)
    $rxIconData = null;
    $rxIconMime = null;
    $rxIconPath = public_path('img/rx-icons.png');
    if (file_exists($rxIconPath)) {
        $rxIconData = base64_encode(file_get_contents($rxIconPath));
        $rxIconMime = mime_content_type($rxIconPath);
    }

    // ✅ Generate PDF
    $pdf = PDF::loadView('patient.prescription_pdf', [
        'prescription'      => $prescription,
        'signatureData'     => $signatureData,
        'signatureMime'     => $signatureMime,
        'clinicLogoData'    => $clinicLogoData,
        'clinicLogoMime'    => $clinicLogoMime,
        'rxIconData'        => $rxIconData,
        'rxIconMime'        => $rxIconMime,
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
