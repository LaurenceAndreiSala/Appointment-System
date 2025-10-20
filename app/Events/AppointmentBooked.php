<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentBooked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->load('patient');
    }

    public function broadcastOn()
    {
        // Send to private channel specific to doctor
        return new PrivateChannel('doctor.' . $this->appointment->doctor_id);
    }

    public function broadcastAs()
    {
        // Custom event name for frontend
        return 'appointment.booked';
    }

    public function broadcastWith()
    {
        // Send appointment details to frontend
        return [
            'id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->firstname . ' ' . $this->appointment->patient->lastname,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'status' => $this->appointment->status,
        ];
    }
}
