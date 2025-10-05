<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AppointmentCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    // Private channel for the doctor
    public function broadcastOn()
    {
        return new PrivateChannel('doctor.' . $this->appointment->doctor_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->firstname . ' ' . $this->appointment->patient->lastname,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'status' => $this->appointment->status,
        ];
    }
}
