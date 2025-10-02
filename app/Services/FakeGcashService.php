<?php

namespace App\Services;

class FakeGcashService
{
    public function createPayment($appointment, $amount)
    {
        // Generate fake reference + redirect URL
        return [
            'reference_number' => strtoupper('GC-' . uniqid()),
            'redirect_url' => route('gcash.mock.checkout', ['id' => $appointment->id]),
        ];
    }

    public function verifyPayment($referenceNumber)
    {
        // In real API, you'd call GCash API here
        // For fake mode, always return success
        return [
            'status' => 'success',
            'paid_at' => now(),
        ];
    }
}
