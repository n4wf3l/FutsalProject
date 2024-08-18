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
        Schema::table('club_info', function (Blueprint $table) {
            $table->string('city')->after('sportcomplex_location'); // Ajoute la colonne city après sportcomplex_location
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_info', function (Blueprint $table) {
            $table->dropColumn('city'); // Supprime la colonne city si on fait un rollback
        });
    }
};
