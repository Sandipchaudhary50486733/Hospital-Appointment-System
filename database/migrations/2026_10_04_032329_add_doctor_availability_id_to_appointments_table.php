<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('doctor_availability_id')
                ->nullable()
                ->after('doctor_id')
                ->constrained('doctor_availabilities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign([
                'doctor_availability_id'
            ]);
            $table->dropColumn([
                'doctor_availability_id'
            ]);
        });
    }
};