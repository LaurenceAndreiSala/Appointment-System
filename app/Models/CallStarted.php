<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class CallStarted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        // Make sure doctor is preloaded in the controller
        $this->appointment = $appointment;
    }

    public function broadcastOn()
    {
        // Patient-specific private channel
        return new PrivateChannel('appointments.' . $this->appointment->patient_id);
    }

    public function broadcastAs()
    {
        return 'CallStarted';
    }

    public function broadcastWith()
    {
        // Clean payload for frontend
        return [
            'appointment' => [
                'id' => $this->appointment->id,
                'meeting_url' => $this->appointment->meeting_url,
                'doctor' => [
                    'id' => $this->appointment->doctor?->id,
                    'firstname' => $this->appointment->doctor?->firstname,
                    'lastname' => $this->appointment->doctor?->lastname,
                ],
            ],
        ];
    }
}
