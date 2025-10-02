<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewAppointmentNotification implements ShouldBroadcast
{
    use SerializesModels;

    public $userId;
    public $title;
    public $message;

    public function __construct($userId, $title, $message)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('doctor.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'NewAppointmentNotification';
    }
}