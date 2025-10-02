<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = [
        'appointment_id',
        'caller_id',
        'receiver_id',
        'meeting_url',
        'status',
    ];

    public function caller() { return $this->belongsTo(User::class, 'caller_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}
