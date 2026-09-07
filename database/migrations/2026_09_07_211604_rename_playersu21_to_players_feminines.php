<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('playersu21') && ! Schema::hasTable('players_feminines')) {
            Schema::rename('playersu21', 'players_feminines');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('players_feminines') && ! Schema::hasTable('playersu21')) {
            Schema::rename('players_feminines', 'playersu21');
        }
    }
};
