<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            // Which appointment this rating belongs to. Each completed
            // appointment can only be rated once (see the unique() below).
            $table->foreignId('appointment_id')->unique()->constrained()->cascadeOnDelete();

            // Who gave the rating (the patient) and who it's for (the doctor).
            // We store both here even though they can be found through the
            // appointment, because it makes querying "all ratings for this
            // doctor" much simpler and faster.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();

            // A whole number from 1 to 5.
            $table->unsignedTinyInteger('rating');

            // Optional written feedback.
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
};
