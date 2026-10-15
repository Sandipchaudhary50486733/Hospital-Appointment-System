<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;
use App\Models\District;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'district_id',
        'name',
        'address',
        'phone',
        'email',
        'description',
        'is_active',
        // 'hospital_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
    
}