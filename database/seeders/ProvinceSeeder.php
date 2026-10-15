<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $provinces = [
            'Koshi Province',
            'Madhesh Province',
            'Bagmati Province',
            'Gandaki Province',
            'Lumbini Province',
            'Karnali Province',
            'Sudurpashchim Province',
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->updateOrInsert(
                ['name' => $province],
                [
                    'name' => $province,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}