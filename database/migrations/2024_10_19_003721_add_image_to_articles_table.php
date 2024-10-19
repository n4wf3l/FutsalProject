<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('image')->nullable(); // Ajoute la colonne image, nullable si l'image n'est pas obligatoire
        });
    }

    public function down(): void {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
