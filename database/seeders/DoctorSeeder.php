<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Amara Chen', 'specialization' => 'Cardiology', 'email' => 'a.chen@meridianhealth.test', 'bio' => 'Board-certified cardiologist with 12 years of experience in interventional cardiology.'],
            ['name' => 'Dr. Daniel Osei', 'specialization' => 'Pediatrics', 'email' => 'd.osei@meridianhealth.test', 'bio' => 'Pediatrician focused on preventive care and childhood development.'],
            ['name' => 'Dr. Priya Nair', 'specialization' => 'Dermatology', 'email' => 'p.nair@meridianhealth.test', 'bio' => 'Dermatologist specializing in skin health and cosmetic dermatology.'],
            ['name' => 'Dr. Marcus Webb', 'specialization' => 'Orthopedics', 'email' => 'm.webb@meridianhealth.test', 'bio' => 'Orthopedic surgeon with a focus on sports injuries and joint replacement.'],
            ['name' => 'Dr. Sofia Reyes', 'specialization' => 'General Medicine', 'email' => 's.reyes@meridianhealth.test', 'bio' => 'Primary care physician providing comprehensive family medicine.'],
        ];

        foreach ($doctors as $doctor) {
            Doctor::firstOrCreate(['email' => $doctor['email']], [...$doctor, 'is_active' => true]);
        }
    }
}
