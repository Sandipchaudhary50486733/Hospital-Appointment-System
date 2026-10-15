<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'hospital_id',
        'name',
        'specialization',
        'license_number',
        'bio',
        'image',
        'is_active',
        'email',
    ];

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function getImageUrlAttribute()
    {
        // Served by MediaController, so it works with or without the
        // public/storage symlink and with any APP_URL / port.
        return $this->image
            ? route('media', ['path' => ltrim($this->image, '/')])
            : null;
    }

    public function getAverageRatingAttribute()
    {
        $average = $this->ratings()->avg('rating');

        return $average ? round($average, 1) : null;
    }

    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }
}

