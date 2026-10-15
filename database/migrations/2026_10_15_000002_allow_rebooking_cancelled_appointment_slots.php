<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * appointments had a UNIQUE (doctor_id, appointment_date, appointment_time)
 * index. A cancelled appointment keeps its row, so the slot could never be
 * booked again (the INSERT threw a duplicate-key error, shown as a 500).
 *
 * Double-booking is prevented in AppointmentController::store() inside a
 * transaction (only pending/confirmed appointments count), so the strict
 * unique index is replaced with a normal lookup index.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Add the plain index first: on MySQL the doctor_id foreign key may be
        // relying on the unique index, and MySQL refuses to drop it otherwise.
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(
                ['doctor_id', 'appointment_date', 'appointment_time'],
                'appointments_doctor_slot_index'
            );
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique(['doctor_id', 'appointment_date', 'appointment_time']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_doctor_slot_index');
        });
    }
};
