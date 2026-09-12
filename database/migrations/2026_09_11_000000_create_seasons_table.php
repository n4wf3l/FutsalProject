<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seasons')) {
            return;
        }

        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('season_label', 32)->unique();
            $table->smallInteger('season_start_year')->index();
            $table->string('division', 64);
            $table->smallInteger('position')->nullable();
            $table->string('position_label', 64)->nullable();
            $table->string('cup_result', 64)->nullable();
            $table->string('coach', 120)->nullable();
            $table->string('badge', 32)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
