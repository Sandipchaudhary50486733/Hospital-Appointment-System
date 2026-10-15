# Meridian Health — Hospital Appointment System

A Laravel 11 hospital appointment system with three roles (patient, doctor,
admin). Tailwind CSS, Vite, SweetAlert2, Font Awesome. SQLite by default
(MySQL also works - change the `DB_*` values in `.env`).

## Quick start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link      # REQUIRED for doctor/profile photos to display
php artisan migrate
php artisan db:seed
npm run build                 # or `npm run dev` while developing
php artisan serve
```

Visit `http://127.0.0.1:8000`.

### Upgrading an existing database

Just run `php artisan migrate`. It will:

* merge duplicate provinces (e.g. "Bagmati" + "Bagmati Province") into one,
  keeping every hospital and district;
* allow a cancelled appointment's time slot to be booked again;
* create the `contact_messages` table (and link it to the sender's account).

## Seeded accounts

| Role   | Email                              | Password   |
|--------|------------------------------------|------------|
| Admin  | `admin@meridianhealth.test`        | `password` |
| Doctor | `sandip.doctor@meridianhealth.test` (and 5 others, see `DoctorSeeder`) | `password` |

Patients register through the UI.

## How it works

* **Patient** - registers, picks Province -> District -> Hospital -> Doctor,
  chooses one of the time slots the doctor has published, books, cancels, and
  rates a doctor after a completed visit.
* **Doctor** - self-registers by choosing an existing hospital. A new doctor is
  **inactive until an admin approves** the profile (Admin -> Doctors -> Edit ->
  tick *Active*). Doctors publish availability slots and confirm / complete /
  cancel appointments. Cancelling frees the slot again.
* **Admin** - dashboard, hospitals, doctors, all appointments, users, settings.
  Deleting a doctor who has appointment history **deactivates** them instead, so
  patients' history and ratings are never lost.
* **Contact form** - every message is saved in the `contact_messages` table
  (with the sender's account if they were logged in) and emailed to
  `CONTACT_NOTIFY_EMAIL` - or to every admin account if that is empty. The
  sender is set as *Reply-To*, so replying to the email answers them directly.
  Admins read, mark read/unread and delete messages under **Admin -> Messages**
  (unread count shows in the sidebar).

### Email setup

By default `MAIL_MAILER=log`, so emails are only written to
`storage/logs/laravel.log` (good for development). To send real email, set SMTP
details in `.env`, for example Gmail with an App Password:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=you@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_FROM_ADDRESS=you@gmail.com
CONTACT_NOTIFY_EMAIL=you@gmail.com
```

then run `php artisan config:clear`. If the mail server is down the message is
still saved and visible in the admin inbox.

Seeders: `DatabaseSeeder` runs `ProvinceSeeder`, `DistrictSeeder`,
`HospitalSeeder`, `AdminSeeder`, `DoctorSeeder`. They are safe to run more than
once.

## Booking integrity

Booking runs in a database transaction and locks the chosen slot
(`SELECT ... FOR UPDATE`), so two patients cannot take the same slot. Only
`pending` / `confirmed` appointments occupy a slot; `cancelled` and `completed`
are final.

## Tests

```bash
php artisan test      # or: vendor/bin/pest
```

Tests use an in-memory SQLite database (see `phpunit.xml`) and never touch your
real database.

## Notes

* Do not share your `.env` file (it is git-ignored); share `.env.example`.
* Not included: replying from inside the app (use *Reply by email*), and
  medical records.
