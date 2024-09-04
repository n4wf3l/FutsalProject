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
        Schema::table('flash_messages', function (Blueprint $table) {
            $table->string('homemessage')->nullable()->after('message'); // Ajouter la colonne homemessage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_messages', function (Blueprint $table) {
            $table->dropColumn('homemessage'); // Supprimer la colonne homemessage si la migration est annulée
        });
    }
};
