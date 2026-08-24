<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Only the relative path is stored — e.g. "doctors/abc123.jpg".
            // The actual file lives in storage/app/public and is served
            // through the public/storage symlink (php artisan storage:link).
            $table->string('image')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
