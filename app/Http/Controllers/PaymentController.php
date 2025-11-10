<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Services\FakeGcashService;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends Controller
{
    public function showForm($id)
    {
        $appointment = Appointment::with('doctor')->findOrFail($id);

        if ($appointment->status !== 'complete') {
            return redirect()->route('patient.view-appointment')
                ->with('error', 'Appointment is not complete yet.');
        }

        return view('patient.payment', compact('appointment'));
    }

   public function process(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);

    $request->validate([
        'payment_method' => 'required|in:gcash,paypal,credit_card',
        'amount' => 'required|numeric|min:1',
    ]);

    if ($request->payment_method === 'gcash') {
        $gcash = new FakeGcashService();
        $payment = $gcash->createPayment($appointment, $request->amount);
        return redirect($payment['redirect_url']);
    }

    // Other methods = instantly mark paid
    $payment = Payment::create([
        'user_id' => auth()->id(),
        'appointment_id' => $appointment->id,
        'payment_method' => $request->payment_method,
        'amount' => $request->amount,
        'payment_status' => 'success',
        'reference_number' => strtoupper(Str::random(10)),
    ]);

    return redirect()->route('patient.view-appointment')
        ->with('success', 'Payment successful. Ref: ' . $payment->reference_number);
}

    public function receipt($id)
{
    $payment = Payment::with(['appointment.doctor', 'user'])->findOrFail($id);

    if ($payment->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    $pdf = Pdf::loadView('patient.receipt', compact('payment'));
    return $pdf->download('receipt-'.$payment->reference_number.'.pdf');
}
}

