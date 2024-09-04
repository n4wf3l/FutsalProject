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
        Schema::table('coaches', function (Blueprint $table) {
            // Ajouter les nouveaux champs
            $table->date('birth_date')->nullable();
            $table->date('coaching_since')->nullable();
            $table->string('birth_city')->nullable();
            $table->string('nationality')->nullable();
            $table->text('description')->nullable();

            // Ajouter un champ pour la photo
            $table->string('photo')->nullable();

            // Supprimer le champ position
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaches', function (Blueprint $table) {
            // Supprimer les champs ajoutés lors du rollback
            $table->dropColumn(['birth_date', 'coaching_since', 'birth_city', 'nationality', 'description', 'photo']);

            // Réajouter le champ position lors du rollback
            $table->string('position')->nullable();
        });
    }
};
