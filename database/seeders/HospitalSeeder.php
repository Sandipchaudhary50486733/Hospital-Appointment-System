<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Real / verified hospital records
        |--------------------------------------------------------------------------
        |
        | These records are preserved when the seeder is executed again.
        |
        */

        $hospitals = [

            // ============================================================
            // KOSHI PROVINCE
            // ============================================================

            // Taplejung
            [
                'province' => 'Koshi Province',
                'district' => 'Taplejung',
                'name' => 'District Hospital Taplejung',
                'address' => 'Phungling, Taplejung',
                'phone' => '9852660188',
                'email' => 'dht.taplejung@gmail.com',
                'description' => 'District-level hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Taplejung',
                'name' => 'Taplejung Community Hospital',
                'address' => 'Phungling, Taplejung',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Panchthar
            [
                'province' => 'Koshi Province',
                'district' => 'Panchthar',
                'name' => 'District Hospital Panchthar',
                'address' => 'Phidim, Panchthar',
                'phone' => '9852684188',
                'email' => 'panchtharhospital@gmail.com',
                'description' => 'District-level hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Panchthar',
                'name' => 'Panchthar Community Hospital',
                'address' => 'Phidim, Panchthar',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Ilam
            [
                'province' => 'Koshi Province',
                'district' => 'Ilam',
                'name' => 'District Hospital Ilam',
                'address' => 'Ilam, Ilam',
                'phone' => '9852680122',
                'email' => 'ilamhospital@gmail.com',
                'description' => 'District-level hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Ilam',
                'name' => 'Ilam Community Hospital',
                'address' => 'Ilam, Ilam',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Jhapa
            [
                'province' => 'Koshi Province',
                'district' => 'Jhapa',
                'name' => 'Provincial Hospital Bhadrapur',
                'address' => 'Bhadrapur, Jhapa',
                'phone' => '9852671615',
                'email' => 'info.provincehospitalbhadrapur@koshi.gov.np',
                'description' => 'Provincial-level hospital providing general and specialized healthcare services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Jhapa',
                'name' => 'AMDA Hospital Damak',
                'address' => 'Damak-02, Jhapa',
                'phone' => '9852672775',
                'email' => null,
                'description' => 'Hospital providing general healthcare and community medical services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Jhapa',
                'name' => 'Advanced Multispecialist Hospital',
                'address' => 'Jhapa, Koshi Province',
                'phone' => '9843205130',
                'email' => null,
                'description' => 'Multispecialty hospital providing general and specialized medical services.',
            ],

            // Morang
            [
                'province' => 'Koshi Province',
                'district' => 'Morang',
                'name' => 'Madan Bhandari Hospital and Trauma Centre',
                'address' => 'Urlabari, Morang',
                'phone' => '9852075140',
                'email' => 'mbhtc.np@gmail.com',
                'description' => 'Hospital and trauma centre providing general medical and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Morang',
                'name' => 'Nobel Medical College',
                'address' => 'Biratnagar-04, Morang',
                'phone' => '9852026445',
                'email' => null,
                'description' => 'Teaching hospital providing specialist healthcare, diagnosis and clinical education.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Morang',
                'name' => 'Aarya Hospital and Neuro Centre',
                'address' => 'Biratnagar-03, Morang',
                'phone' => '9841322487',
                'email' => null,
                'description' => 'Hospital and neuro centre providing general and neurological healthcare.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Morang',
                'name' => 'Koshi Hospital',
                'address' => 'Biratnagar, Morang',
                'phone' => null,
                'email' => null,
                'description' => 'Major hospital providing general and specialist medical services.',
            ],

            // Sunsari
            [
                'province' => 'Koshi Province',
                'district' => 'Sunsari',
                'name' => 'District Hospital Sunsari',
                'address' => 'Inaruwa, Sunsari',
                'phone' => '9852065918',
                'email' => 'Districthospitalsunsari756@gmail.com',
                'description' => 'District-level hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Sunsari',
                'name' => 'B.P. Koirala Institute of Health Sciences',
                'address' => 'Dharan-18, Sunsari',
                'phone' => '9842491579',
                'email' => null,
                'description' => 'Major teaching and referral hospital providing specialized medical services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Sunsari',
                'name' => 'Itahari Hospital',
                'address' => 'Itahari, Sunsari',
                'phone' => null,
                'email' => null,
                'description' => 'Hospital providing general medical and emergency services.',
            ],

            // Dhankuta
            [
                'province' => 'Koshi Province',
                'district' => 'Dhankuta',
                'name' => 'District Hospital Dhankuta',
                'address' => 'Dhankuta, Dhankuta',
                'phone' => '9860777066',
                'email' => 'dhankutahospital7@gmail.com',
                'description' => 'District hospital providing general and emergency healthcare services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Dhankuta',
                'name' => 'Dhankuta Community Hospital',
                'address' => 'Dhankuta, Dhankuta',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Terhathum
            [
                'province' => 'Koshi Province',
                'district' => 'Terhathum',
                'name' => 'District Hospital Terhathum',
                'address' => 'Myanglung, Terhathum',
                'phone' => '9852060188',
                'email' => 'dhterhathum@gmail.com',
                'description' => 'District hospital providing general treatment and emergency healthcare.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Terhathum',
                'name' => 'Terhathum Community Hospital',
                'address' => 'Myanglung, Terhathum',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Sankhuwasabha
            [
                'province' => 'Koshi Province',
                'district' => 'Sankhuwasabha',
                'name' => 'District Hospital Sankhuwasabha',
                'address' => 'Khandbari, Sankhuwasabha',
                'phone' => '9852032188',
                'email' => 'sankhuwasabha.hos@gmail.com',
                'description' => 'District hospital providing general treatment and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Sankhuwasabha',
                'name' => 'Sankhuwasabha Community Hospital',
                'address' => 'Khandbari, Sankhuwasabha',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Bhojpur
            [
                'province' => 'Koshi Province',
                'district' => 'Bhojpur',
                'name' => 'District Hospital Bhojpur',
                'address' => 'Bhojpur, Bhojpur',
                'phone' => '9852052188',
                'email' => 'hospitalbhp@gmail.com',
                'description' => 'District hospital providing general treatment and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Bhojpur',
                'name' => 'Bhojpur Community Hospital',
                'address' => 'Bhojpur, Bhojpur',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Solukhumbu
            [
                'province' => 'Koshi Province',
                'district' => 'Solukhumbu',
                'name' => 'District Hospital Solukhumbu',
                'address' => 'Phaplu, Solukhumbu',
                'phone' => '9852885188',
                'email' => 'soluhsp78@gmail.com',
                'description' => 'District hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Solukhumbu',
                'name' => 'Solukhumbu Community Hospital',
                'address' => 'Phaplu, Solukhumbu',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Okhaldhunga
            [
                'province' => 'Koshi Province',
                'district' => 'Okhaldhunga',
                'name' => 'District Hospital Okhaldhunga',
                'address' => 'Rumjatar, Okhaldhunga',
                'phone' => '9852844188',
                'email' => 'rumjatarhospital@gmail.com',
                'description' => 'District hospital providing general treatment and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Okhaldhunga',
                'name' => 'Okhaldhunga Community Hospital',
                'address' => 'Rumjatar, Okhaldhunga',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Khotang
            [
                'province' => 'Koshi Province',
                'district' => 'Khotang',
                'name' => 'District Hospital Khotang',
                'address' => 'Diktel, Khotang',
                'phone' => '9852849576',
                'email' => 'districthospital.khotang@gmail.com',
                'description' => 'District hospital providing general healthcare and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Khotang',
                'name' => 'Khotang Community Hospital',
                'address' => 'Diktel, Khotang',
                'phone' => null,
                'email' => null,
                'description' => 'Additional demo hospital record for community healthcare services.',
            ],

            // Udayapur
            [
                'province' => 'Koshi Province',
                'district' => 'Udayapur',
                'name' => 'District Hospital Udayapur',
                'address' => 'Gaighat, Udayapur',
                'phone' => '9852866700',
                'email' => 'udp700@gmail.com',
                'description' => 'District hospital providing general treatment and emergency services.',
            ],

            [
                'province' => 'Koshi Province',
                'district' => 'Udayapur',
                'name' => 'Katari Hospital',
                'address' => 'Katari, Udayapur',
                'phone' => '9852836188',
                'email' => 'hospitalkatari@gmail.com',
                'description' => 'Hospital providing general medical treatment and emergency services.',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | All remaining districts
        |--------------------------------------------------------------------------
        |
        | These are demo records so every district has multiple hospitals.
        | Replace them later with verified hospital institutions if needed.
        |
        */

        $districts = [

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

        /*
        |--------------------------------------------------------------------------
        | Create two demo hospitals for every remaining district
        |--------------------------------------------------------------------------
        */

        foreach ($districts as $provinceName => $districtList) {

            foreach ($districtList as $districtName) {

                $hospitals[] = [
                    'province' => $provinceName,
                    'district' => $districtName,
                    'name' => 'District Hospital ' . $districtName,
                    'address' => $districtName . ', Nepal',
                    'phone' => null,
                    'email' => null,
                    'description' => 'District-level hospital providing general healthcare and emergency services.',
                ];

                $hospitals[] = [
                    'province' => $provinceName,
                    'district' => $districtName,
                    'name' => $districtName . ' Community Hospital',
                    'address' => $districtName . ', Nepal',
                    'phone' => null,
                    'email' => null,
                    'description' => 'Community hospital providing general healthcare and basic medical services.',
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insert / update hospitals
        |--------------------------------------------------------------------------
        */

        $count = 0;

        foreach ($hospitals as $hospital) {

            $province = DB::table('provinces')
                ->where('name', $hospital['province'])
                ->first();

            if (!$province) {
                $this->command->warn(
                    "Province not found: {$hospital['province']}"
                );

                continue;
            }

            $district = DB::table('districts')
                ->where('province_id', $province->id)
                ->where('name', $hospital['district'])
                ->first();

            if (!$district) {
                $this->command->warn(
                    "District not found: {$hospital['district']}"
                );

                continue;
            }

            DB::table('hospitals')->updateOrInsert(
                [
                    'name' => $hospital['name'],
                    'district_id' => $district->id,
                ],
                [
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'name' => $hospital['name'],
                    'address' => $hospital['address'],
                    'phone' => $hospital['phone'],
                    'email' => $hospital['email'],
                    'description' => $hospital['description'],
                    'is_active' => true,
                    'updated_at' => $now,
                ]
            );

            $count++;
        }

        $this->command->info(
            "{$count} hospital records processed successfully."
        );
    }
}