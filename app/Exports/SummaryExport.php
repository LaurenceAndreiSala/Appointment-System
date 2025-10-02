<?php
namespace App\Exports;

use App\Models\Appointment;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromArray;

class SummaryExport implements FromArray
{
    public function array(): array
    {
        $completed = Appointment::where('status', 'confirmed')->count();
        $cancelled = Appointment::where('status', 'cancelled')->count();
        $pending   = Appointment::where('status', 'pending')->count();
        $totalPayments = Payment::where('payment_status', 'success')->sum('amount');

        return [
            ['Metric', 'Value'],
            ['Completed Appointments', $completed],
            ['Pending Appointments', $pending],
            ['Cancelled Appointments', $cancelled],
            ['Total Revenue (₱)', $totalPayments],
        ];
    }
}
