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
        Schema::table('background_images', function (Blueprint $table) {
            $table->string('assigned_page')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('background_images', function (Blueprint $table) {
            $table->dropColumn('assigned_page');
        });
    }
};
