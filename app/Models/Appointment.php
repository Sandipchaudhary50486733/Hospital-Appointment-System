<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DoctorAvailability;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'contact_number',
        'type',
        'status',
        'doctor_availability_id',
    ];

    protected function casts()
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function doctorAvailability(){
        return $this->belongsTo(DoctorAvailability::class, 'doctor_availability_id');
    }

    // A completed appointment can have at most one rating attached to it.
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
