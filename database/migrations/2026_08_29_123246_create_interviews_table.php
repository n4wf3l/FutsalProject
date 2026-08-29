<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('interviewee_name');
            $table->string('interviewee_role');
            $table->string('interviewee_affiliation')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('interviewee_photo')->nullable();
            $table->string('video_url')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('quote_highlight')->nullable();
            $table->longText('content');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('published_at');
            $table->index('interviewee_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
