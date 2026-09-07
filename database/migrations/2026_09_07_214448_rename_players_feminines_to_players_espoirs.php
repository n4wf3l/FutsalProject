<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('players_feminines') && ! Schema::hasTable('players_espoirs')) {
            Schema::rename('players_feminines', 'players_espoirs');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('players_espoirs') && ! Schema::hasTable('players_feminines')) {
            Schema::rename('players_espoirs', 'players_feminines');
        }
    }
};
