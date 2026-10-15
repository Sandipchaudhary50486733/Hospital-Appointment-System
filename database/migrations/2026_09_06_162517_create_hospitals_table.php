<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();

            // province_id and district_id are intentionally NOT defined here.
            // They're added later by add_province_and_district_to_hospitals_table,
            // which runs after provinces/districts exist. Defining them here too
            // would (a) fail because those tables don't exist yet at this point
            // in migration order, and (b) cause a "duplicate column" error once
            // the later migration tries to add them again.

            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
