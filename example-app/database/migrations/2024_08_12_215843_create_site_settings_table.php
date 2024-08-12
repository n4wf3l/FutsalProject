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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('theme_color_primary')->default('#1F2937'); // Default gray
            $table->string('theme_color_secondary')->default('#FF0000'); // Default red
            $table->string('club_name')->default('Default Club Name');
            $table->string('logo')->nullable(); // Nullable if no logo is set
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
