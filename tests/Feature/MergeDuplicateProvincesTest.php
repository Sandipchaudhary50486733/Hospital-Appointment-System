<?php

use Illuminate\Support\Facades\DB;

it('merges "Bagmati" into "Bagmati Province" without losing hospitals', function () {
    $now = now();

    $long = DB::table('provinces')->insertGetId(['name' => 'Bagmati Province', 'created_at' => $now, 'updated_at' => $now]);
    $short = DB::table('provinces')->insertGetId(['name' => 'Bagmati', 'created_at' => $now, 'updated_at' => $now]);

    $kathmanduLong = DB::table('districts')->insertGetId(['province_id' => $long, 'name' => 'Kathmandu', 'created_at' => $now, 'updated_at' => $now]);
    $kathmanduShort = DB::table('districts')->insertGetId(['province_id' => $short, 'name' => 'Kathmandu', 'created_at' => $now, 'updated_at' => $now]);
    DB::table('districts')->insert(['province_id' => $short, 'name' => 'OnlyInShort', 'created_at' => $now, 'updated_at' => $now]);

    DB::table('hospitals')->insert(['name' => 'H1', 'province_id' => $long, 'district_id' => $kathmanduLong, 'created_at' => $now, 'updated_at' => $now]);
    DB::table('hospitals')->insert(['name' => 'H2', 'province_id' => $short, 'district_id' => $kathmanduShort, 'created_at' => $now, 'updated_at' => $now]);

    (require database_path('migrations/2026_10_15_000001_merge_duplicate_provinces.php'))->up();

    expect(DB::table('provinces')->where('name', 'Bagmati')->exists())->toBeFalse();
    expect(DB::table('provinces')->where('name', 'Bagmati Province')->count())->toBe(1);

    // one Kathmandu, plus the district that only existed in the duplicate
    expect(DB::table('districts')->where('province_id', $long)->where('name', 'Kathmandu')->count())->toBe(1);
    expect(DB::table('districts')->where('province_id', $long)->where('name', 'OnlyInShort')->exists())->toBeTrue();

    // both hospitals survive and now point at the surviving province/district
    expect(DB::table('hospitals')->count())->toBe(2);
    expect(DB::table('hospitals')->where('province_id', $long)->where('district_id', $kathmanduLong)->count())->toBe(2);
});

it('is a no-op when there are no duplicates', function () {
    $now = now();
    DB::table('provinces')->insert(['name' => 'Koshi Province', 'created_at' => $now, 'updated_at' => $now]);

    (require database_path('migrations/2026_10_15_000001_merge_duplicate_provinces.php'))->up();

    expect(DB::table('provinces')->pluck('name')->all())->toBe(['Koshi Province']);
});

it('renames a lone short-name province instead of dropping it', function () {
    $now = now();
    DB::table('provinces')->insert(['name' => 'Karnali', 'created_at' => $now, 'updated_at' => $now]);

    (require database_path('migrations/2026_10_15_000001_merge_duplicate_provinces.php'))->up();

    expect(DB::table('provinces')->pluck('name')->all())->toBe(['Karnali Province']);
});
