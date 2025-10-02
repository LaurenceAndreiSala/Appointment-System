<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableSlot extends Model
{
    use HasFactory;

        protected $fillable = [
    'patient_id',
    'doctor_id',
    'sub_doctor_id',
    'appointment_date',
    'appointment_time',
    'status',
    'date',
    'reason',
    'end_time',
    'start_time',
    'type_online',
    'slot_id',
    'is_taken' // <--- add this
];

public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}

public function slot()
{
    return $this->belongsTo(AvailableSlot::class, 'slot_id');
}

public function subDoctor()
{
    return $this->belongsTo(User::class, 'sub_doctor_id');
}
}
