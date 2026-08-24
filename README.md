# Meridian Health — Hospital Appointment System

A complete, ready-to-run Laravel project — this is the **actual Laravel skeleton**
(composer.json, config/, public/, artisan, the works) with the full hospital
system already merged in. Not a set of files to copy into a separate install —
just `cd` in and run it.

**Stack:** Laravel 11 · Tailwind CSS · SweetAlert2 · Font Awesome · Vite · SQLite (zero-config DB)

## Quick start

```bash
cd meridian-health
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link      # REQUIRED for doctor photo uploads to be viewable
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

Visit `http://127.0.0.1:8000`.

For active development, run `npm run dev` in a second terminal instead of
`npm run build` for hot-reloading.

**Database is SQLite by default** — `database/database.sqlite` is already
created, no MySQL/Postgres server needed. Perfect for a university
submission: examiners can clone and run it with zero external setup. If you'd
rather use MySQL, change `DB_CONNECTION` and the `DB_*` values in `.env`.

## Seeded login

```
Admin:  admin@meridianhealth.test / password
```
5 sample doctors are also seeded (Cardiology, Pediatrics, Dermatology,
Orthopedics, General Medicine) — register your own patient/doctor accounts
through the UI to test those flows.

## What's inside

### Pages
Home · Services · About · Contact Us · Doctors directory · Login · Register
(patient) · Register (doctor) · Patient home dashboard · Appointment booking ·
Doctor dashboard · Admin panel

### Admin panel — controls the whole site
| Section | Route |
|---|---|
| Dashboard (stats) | `/admin` |
| Doctors (add/edit/deactivate, photo upload) | `/admin/doctors` |
| Appointments (view all, filter by status) | `/admin/appointments` |
| Users (browse patients & doctors) | `/admin/users` |

### Backend architecture
- **Models**: `User`, `Doctor`, `Appointment` — with proper relationships (`hasMany`/`belongsTo`)
- **Migrations**: 4 custom (on top of Laravel's 3 default ones) — profile fields on `users`, `doctors`, `appointments`, and `image` on `doctors`
- **Controllers**: one per concern — auth (patient/doctor register, login), patient appointments, doctor appointments, admin (dashboard/doctors/appointments/users), public pages, contact
- **Form Requests**: `RegisterRequest`, `DoctorRegisterRequest`, `LoginRequest`, `StoreAppointmentRequest`, `Admin\DoctorManageRequest` — all server-side validation, can't be bypassed by disabling JS
- **Middleware**: custom `EnsureUserHasRole` (aliased as `role` in `bootstrap/app.php`) guards every `role:patient` / `role:doctor` / `role:admin` route group
- **Routes**: `routes/web.php` — public routes, `guest`-only auth routes, and three role-gated groups

### Frontend
- **Tailwind CSS** utility classes throughout (`resources/css/app.css` defines a few reusable component classes: `.btn-primary`, `.card`, `.field-input`, `.status-pill`)
- **SweetAlert2** — flash messages (success/error) auto-fire from Laravel session data via a hidden `#flash-data` div read by `resources/js/app.js`; "cancel appointment" and "delete doctor" forms show a SweetAlert confirm dialog before submitting (`<form data-confirm="...">`)
- **Font Awesome** — bundled via npm, imported in `app.js` (no CDN dependency)
- **Vite** — compiles CSS/JS; `@vite(...)` directive in every layout/auth page

### Image upload (doctor photos)
1. Admin uploads a photo in the doctor form → `multipart/form-data`
2. `Admin\DoctorController` calls `$request->file('image')->store('doctors', 'public')` — saves to `storage/app/public/doctors/`
3. **Only the relative path** (e.g. `doctors/ab12cd.jpg`) is saved in the `doctors.image` column — never the file itself
4. `Doctor::getImageUrlAttribute()` converts that path to a public URL on demand, used wherever a photo is displayed (admin list, public directory, homepage)
5. This only resolves to a real URL because of the `storage:link` symlink — **skipping that command is the most common reason uploaded images don't show up**

## One important note on this delivery

I generated every application file myself (models, controllers, views, config),
but `vendor/` (PHP dependencies) and the compiled `public/build/` (from
`npm run build`) are **not included** — those come from `composer install` /
`npm run build`, which need registry access this environment doesn't have.
This is normal for any Laravel project handed to you as source — running
those two commands is the standard first step for any Laravel project, not
something specific to this one.

## Not included (kept scope tight on request)

Medical records, an admin contact-message inbox, and email notifications
were left out. All are straightforward to add back if needed.
