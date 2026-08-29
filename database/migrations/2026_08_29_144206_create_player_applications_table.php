<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 40);
            $table->date('birthdate');
            $table->string('nationality')->nullable();
            $table->string('city')->nullable();
            $table->string('category', 40);
            $table->string('position_preference', 60)->nullable();
            $table->string('current_club')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->text('message')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_applications');
    }
};
