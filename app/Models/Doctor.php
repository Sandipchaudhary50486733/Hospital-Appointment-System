<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
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

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Builds a usable image URL from the stored file path, or null if the
    // doctor has no photo uploaded.
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    // Average of all ratings for this doctor, rounded to 1 decimal place.
    // Returns null if nobody has rated this doctor yet, so views can show
    // "No ratings yet" instead of a misleading 0.0.
    public function getAverageRatingAttribute()
    {
        $average = $this->ratings()->avg('rating');

        return $average ? round($average, 1) : null;
    }

    // How many ratings this doctor has received in total.
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }
}
