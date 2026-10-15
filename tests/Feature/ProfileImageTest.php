<?php

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    // A tiny valid 1x1 PNG
    $this->png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
});

it('serves uploaded images from storage without the public/storage symlink', function () {
    Storage::disk('public')->put('profiles/abc.png', $this->png);

    $this->get(route('media', ['path' => 'profiles/abc.png']))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png');
});

it('only serves files from the doctors and profiles folders', function () {
    Storage::disk('public')->put('secret/hidden.png', $this->png);
    Storage::disk('public')->put('profiles/ok.png', $this->png);

    $this->get('/media/secret/hidden.png')->assertNotFound();
    $this->get('/media/profiles/missing.png')->assertNotFound();
    $this->get('/media/profiles/..%2F..%2F.env')->assertNotFound();
    $this->get('/media/profiles/ok.png')->assertOk();
});

it('shows the patient photo on the profile page and in the header', function () {
    Storage::disk('public')->put('profiles/pat.png', $this->png);
    $patient = User::create([
        'name' => 'Pat', 'email' => 'p@p.test', 'password' => 'password',
        'role' => 'patient', 'image' => 'profiles/pat.png',
    ]);

    $url = route('media', ['path' => 'profiles/pat.png']);

    $this->actingAs($patient)->get(route('patient.profile'))
        ->assertOk()->assertSee($url, false);

    $this->actingAs($patient)->get(route('patient.profile.edit'))
        ->assertOk()->assertSee($url, false);
});

it('shows the doctor photo on the profile page, header and public pages', function () {
    Storage::disk('public')->put('doctors/doc.png', $this->png);
    $user = User::create(['name' => 'Dr D', 'email' => 'd@d.test', 'password' => 'password', 'role' => 'doctor']);
    $hospital = Hospital::create(['name' => 'H', 'is_active' => true]);
    $doctor = Doctor::create([
        'user_id' => $user->id, 'hospital_id' => $hospital->id, 'name' => 'Dr D',
        'specialization' => 'Gen', 'license_number' => 'L1', 'is_active' => true,
        'image' => 'doctors/doc.png',
    ]);

    $url = route('media', ['path' => 'doctors/doc.png']);

    $this->actingAs($user)->get(route('doctor.profile'))->assertOk()->assertSee($url, false);
    $this->get(route('public.doctors'))->assertOk()->assertSee($url, false);
    $this->get(route('public.doctor', $doctor))->assertOk()->assertSee($url, false);
});

it('stores a newly uploaded patient photo and then displays it', function () {
    $patient = User::create(['name' => 'Pat', 'email' => 'p@p.test', 'password' => 'password', 'role' => 'patient']);

    $this->actingAs($patient)->put(route('patient.profile.update'), [
        'name' => 'Pat', 'email' => 'p@p.test',
        'image' => UploadedFile::fake()->createWithContent('me.png', $this->png),
    ])->assertSessionHasNoErrors();

    $patient->refresh();
    expect($patient->image)->toStartWith('profiles/');
    Storage::disk('public')->assertExists($patient->image);

    $this->get($patient->image_url)->assertOk();
});
