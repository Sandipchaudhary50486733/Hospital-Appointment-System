<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $data = [

            'Koshi Province' => [
                'Taplejung',
                'Panchthar',
                'Ilam',
                'Jhapa',
                'Morang',
                'Sunsari',
                'Dhankuta',
                'Terhathum',
                'Sankhuwasabha',
                'Bhojpur',
                'Solukhumbu',
                'Okhaldhunga',
                'Khotang',
                'Udayapur',
            ],

            'Madhesh Province' => [
                'Saptari',
                'Siraha',
                'Dhanusha',
                'Mahottari',
                'Sarlahi',
                'Rautahat',
                'Bara',
                'Parsa',
            ],

            'Bagmati Province' => [
                'Dolakha',
                'Ramechhap',
                'Sindhuli',
                'Rasuwa',
                'Sindhupalchok',
                'Kavrepalanchok',
                'Kathmandu',
                'Bhaktapur',
                'Lalitpur',
                'Nuwakot',
                'Dhading',
                'Makwanpur',
                'Chitwan',
            ],

            'Gandaki Province' => [
                'Gorkha',
                'Manang',
                'Mustang',
                'Myagdi',
                'Kaski',
                'Lamjung',
                'Tanahun',
                'Nawalpur',
                'Syangja',
                'Parbat',
                'Baglung',
            ],

            'Lumbini Province' => [
                'Rukum East',
                'Rolpa',
                'Pyuthan',
                'Gulmi',
                'Arghakhanchi',
                'Palpa',
                'Nawalparasi West',
                'Rupandehi',
                'Kapilvastu',
                'Dang',
                'Banke',
                'Bardiya',
            ],

            'Karnali Province' => [
                'Dolpa',
                'Humla',
                'Jumla',
                'Kalikot',
                'Mugu',
                'Surkhet',
                'Dailekh',
                'Jajarkot',
                'Salyan',
                'Rukum West',
            ],

            'Sudurpashchim Province' => [
                'Bajura',
                'Bajhang',
                'Darchula',
                'Baitadi',
                'Doti',
                'Achham',
                'Dadeldhura',
                'Kanchanpur',
                'Kailali',
            ],
        ];

        $total = 0;

        foreach ($data as $provinceName => $districts) {

            $province = DB::table('provinces')
                ->where('name', $provinceName)
                ->first();

            if (!$province) {
                $this->command->error(
                    "Province not found: {$provinceName}"
                );

                continue;
            }

            foreach ($districts as $districtName) {

                DB::table('districts')->updateOrInsert(
                    [
                        'province_id' => $province->id,
                        'name' => $districtName,
                    ],
                    [
                        'province_id' => $province->id,
                        'name' => $districtName,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );

                $total++;
            }
        }

        $this->command->info(
            "{$total} districts seeded successfully."
        );
    }
}