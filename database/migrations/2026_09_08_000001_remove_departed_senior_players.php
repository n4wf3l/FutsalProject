<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $toRemove = [
            ['Mehdi', 'El Kahlaoui'],
            ['Mehdi', 'Kahlaoui'],
            ['Otman', 'Idel Aouad'],
            ['Otman Idel', 'Aouad'],
            ['Otman', 'Aouad'],
        ];

        foreach ($toRemove as [$first, $last]) {
            DB::table('players')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->delete();
        }
    }

    public function down(): void
    {
        // Departure is intentional: no rollback.
    }
};
