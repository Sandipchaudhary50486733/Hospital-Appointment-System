<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guarded: some installs already have users.image, and on a fresh
        // database an unguarded "add column" fails with "duplicate column".
        if (Schema::hasColumn('users', 'image')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('image')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'image')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
