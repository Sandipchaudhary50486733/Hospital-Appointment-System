<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Admins are not self-registered for security reasons — seed the
     * first one, then create additional admins from the admin panel
     * (or `php artisan tinker`) as needed.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@meridianhealth.test'],
            [
                'name'     => 'Site Administrator',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
