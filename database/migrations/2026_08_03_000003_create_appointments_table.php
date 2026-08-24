<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('reason')->nullable();
            $table->string('contact_number', 20);
            $table->enum('type', ['new_patient', 'follow_up'])->default('new_patient');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                ->default('pending');
            $table->timestamps();

            // Prevent double-booking the same doctor at the same date/time
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
