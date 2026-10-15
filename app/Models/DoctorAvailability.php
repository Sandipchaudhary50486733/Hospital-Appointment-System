<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAvailability extends Model
{
    protected $fillable = [
        'doctor_id',
        'available_date',
        'available_time',
        'is_booked',
    ];

    protected $casts = [
        'available_date' => 'date',
        'is_booked' => 'boolean',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'doctor_availability_id');
    }
}