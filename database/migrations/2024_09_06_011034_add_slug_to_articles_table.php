<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter la colonne 'slug' à la table 'articles'
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Générer des slugs temporaires pour les articles existants
        DB::table('articles')->get()->each(function ($article) {
            $slug = \Illuminate\Support\Str::slug($article->title) ?: 'article-' . $article->id;
            DB::table('articles')->where('id', $article->id)->update(['slug' => $slug]);
        });

        // Ajouter la contrainte d'unicité sur le champ slug
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la colonne 'slug'
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
