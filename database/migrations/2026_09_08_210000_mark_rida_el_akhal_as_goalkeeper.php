<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('players_espoirs')
            ->where('first_name', 'Rida')
            ->where('last_name', 'El Akhal')
            ->update([
                'position' => 'Gardien',
                'updated_at' => Carbon::now(),
            ]);
    }

    public function down(): void
    {
        // Position correction is intentional; no rollback.
    }
};
