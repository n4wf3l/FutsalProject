<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('interviews')) {
            return;
        }

        // Voix du Futsal now supports pure chronicles / editorials with no
        // interviewed guest, so the guest name and role become optional.
        Schema::table('interviews', function (Blueprint $table) {
            $table->string('interviewee_name')->nullable()->change();
            $table->string('interviewee_role')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('interviews')) {
            return;
        }

        Schema::table('interviews', function (Blueprint $table) {
            $table->string('interviewee_name')->nullable(false)->change();
            $table->string('interviewee_role')->nullable(false)->change();
        });
    }
};
