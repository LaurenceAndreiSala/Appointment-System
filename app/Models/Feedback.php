<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id', 'rating', 'comments'
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

