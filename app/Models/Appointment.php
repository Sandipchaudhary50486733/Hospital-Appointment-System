<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // A completed appointment can have at most one rating attached to it.
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
