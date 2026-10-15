<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Sandip Chaudhary',
                'email' => 'sandip.doctor@meridianhealth.test',
                'specialization' => 'General Medicine',
                'license_number' => 'NMC-10001',
                'bio' => 'Experienced doctor providing general medical consultation and treatment.',
                'hospital' => 'Itahari Hospital',
            ],

            [
                'name' => 'Dr. Amara Chen',
                'email' => 'amara.chen@meridianhealth.test',
                'specialization' => 'Cardiology',
                'license_number' => 'NMC-10002',
                'bio' => 'Specialist doctor providing cardiovascular consultation and treatment.',
                'hospital' => 'Nobel Medical College',
            ],

            [
                'name' => 'Dr. Daniel Osei',
                'email' => 'daniel.osei@meridianhealth.test',
                'specialization' => 'Orthopedics',
                'license_number' => 'NMC-10003',
                'bio' => 'Orthopedic specialist providing diagnosis and treatment of musculoskeletal conditions.',
                'hospital' => 'B.P. Koirala Institute of Health Sciences',
            ],

            [
                'name' => 'Dr. Priya Nair',
                'email' => 'priya.nair@meridianhealth.test',
                'specialization' => 'Pediatrics',
                'license_number' => 'NMC-10004',
                'bio' => 'Pediatric specialist providing healthcare services for children.',
                'hospital' => 'Itahari Hospital',
            ],

            [
                'name' => 'Dr. Marcus Webb',
                'email' => 'marcus.webb@meridianhealth.test',
                'specialization' => 'Dermatology',
                'license_number' => 'NMC-10005',
                'bio' => 'Dermatology specialist providing consultation and treatment for skin conditions.',
                'hospital' => 'Nobel Medical College',
            ],

            [
                'name' => 'Dr. Sofia Reyes',
                'email' => 'sofia.reyes@meridianhealth.test',
                'specialization' => 'Gynecology',
                'license_number' => 'NMC-10006',
                'bio' => 'Gynecology specialist providing women’s healthcare and medical consultation.',
                'hospital' => 'Itahari Hospital',
            ],
        ];

        foreach ($doctors as $data) {

            $hospital = Hospital::where('name', $data['hospital'])->first();

            if (!$hospital) {
                $this->command->warn(
                    "Hospital not found: {$data['hospital']}"
                );

                continue;
            }

            /*
             * Create or find the doctor user account.
             */
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                ]
            );

            /*
             * Create or update doctor record.
             */
            Doctor::updateOrCreate(
                ['email' => $data['email']],
                [
                    'user_id' => $user->id,
                    'hospital_id' => $hospital->id,
                    'name' => $data['name'],
                    'specialization' => $data['specialization'],
                    'license_number' => $data['license_number'],
                    'bio' => $data['bio'],
                    'image' => null,
                    'is_active' => true,
                    'email' => $data['email'],
                ]
            );
        }

        $this->command->info(count($doctors) . ' doctors processed successfully.');
    }
}