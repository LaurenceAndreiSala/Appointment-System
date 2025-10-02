<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->load('doctor');
    }

    public function broadcastOn()
    {
        // Private channel for this patient
        return new Channel("appointments." . $this->appointment->patient_id);
    }

    public function broadcastWith()
    {
        return [
            'appointment' => [
                'id' => $this->appointment->id,
                'meeting_url' => $this->appointment->meeting_url,
                'doctor' => [
                    'firstname' => $this->appointment->doctor->firstname,
                    'lastname'  => $this->appointment->doctor->lastname,
                ],
            ],
        ];
    }
}
