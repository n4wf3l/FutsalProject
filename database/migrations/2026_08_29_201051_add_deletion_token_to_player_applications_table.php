<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('player_applications', function (Blueprint $table) {
            $table->string('deletion_token', 64)->nullable()->unique()->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('player_applications', function (Blueprint $table) {
            $table->dropUnique(['deletion_token']);
            $table->dropColumn('deletion_token');
        });
    }
};
