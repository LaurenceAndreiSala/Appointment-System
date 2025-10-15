<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
    'patient_id',
    'doctor_id',
    'appointment_date',
    'appointment_time',
    'status',
    'reason',
    'slot_id',
    'is_read',
    'type_online',
    'meeting_url',
    'height',
    'weight',
    'bmi',
    'blood_type',
    'advice',
];
public function patient()
{
    return $this->belongsTo(User::class, 'patient_id');
}

public function slot()
{
    return $this->belongsTo(AvailableSlot::class, 'slot_id');
}

public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}

public function payment()
{
    return $this->hasOne(Payment::class);
}

    public function prescription() { return $this->hasOne(Prescription::class); }
    public function feedback() { return $this->hasOne(Feedback::class); }
}

