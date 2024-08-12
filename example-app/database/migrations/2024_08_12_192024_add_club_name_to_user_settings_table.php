<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->string('club_name')->nullable(); // Ajoute une colonne club_name
        });
    }
    
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('club_name'); // Supprime la colonne club_name si la migration est annulée
        });
    }
};
