<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Two different seeders used to create provinces: one named them "Bagmati"
 * and the other "Bagmati Province", so every province appeared twice in the
 * booking dropdown. This migration keeps the "<Name> Province" record (the one
 * the hospitals are attached to) and folds the short-name duplicate into it.
 *
 * It is safe to run on a database that has no duplicates (it does nothing).
 */
return new class extends Migration
{
    public function up(): void
    {
        $shortNames = [
            'Koshi',
            'Madhesh',
            'Bagmati',
            'Gandaki',
            'Lumbini',
            'Karnali',
            'Sudurpashchim',
        ];

        DB::transaction(function () use ($shortNames) {
            foreach ($shortNames as $short) {
                $duplicate = DB::table('provinces')->where('name', $short)->first();

                if (! $duplicate) {
                    continue;
                }

                $canonical = DB::table('provinces')
                    ->where('name', $short . ' Province')
                    ->first();

                // Only the short name exists: just give it the full name.
                if (! $canonical) {
                    DB::table('provinces')
                        ->where('id', $duplicate->id)
                        ->update(['name' => $short . ' Province']);

                    continue;
                }

                // Move (or merge) every district of the duplicate.
                $districts = DB::table('districts')
                    ->where('province_id', $duplicate->id)
                    ->get();

                foreach ($districts as $district) {
                    $existing = DB::table('districts')
                        ->where('province_id', $canonical->id)
                        ->where('name', $district->name)
                        ->first();

                    if ($existing) {
                        // Same district already exists under the right
                        // province: repoint anything using the duplicate.
                        DB::table('hospitals')
                            ->where('district_id', $district->id)
                            ->update([
                                'district_id' => $existing->id,
                                'province_id' => $canonical->id,
                            ]);

                        DB::table('districts')->where('id', $district->id)->delete();
                    } else {
                        DB::table('districts')
                            ->where('id', $district->id)
                            ->update(['province_id' => $canonical->id]);
                    }
                }

                DB::table('hospitals')
                    ->where('province_id', $duplicate->id)
                    ->update(['province_id' => $canonical->id]);

                DB::table('provinces')->where('id', $duplicate->id)->delete();
            }

            // "Nawalparasi East" belongs to Gandaki (as "Nawalpur"), not Lumbini.
            // It only ever came from the removed duplicate seeder.
            $lumbini = DB::table('provinces')->where('name', 'Lumbini Province')->first();

            if ($lumbini) {
                $stray = DB::table('districts')
                    ->where('province_id', $lumbini->id)
                    ->where('name', 'Nawalparasi East')
                    ->first();

                if ($stray && ! DB::table('hospitals')->where('district_id', $stray->id)->exists()) {
                    DB::table('districts')->where('id', $stray->id)->delete();
                }
            }
        });
    }

    public function down(): void
    {
        // Data clean-up only; nothing to reverse.
    }
};
