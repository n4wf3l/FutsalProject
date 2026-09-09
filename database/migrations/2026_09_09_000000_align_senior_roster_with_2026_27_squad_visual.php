<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        // 1. Reda no longer in the squad visual: remove the placeholder record.
        DB::table('players')
            ->where('first_name', 'Reda')
            ->where('last_name', 'À compléter')
            ->delete();

        // 2. Soufiane Jbari is back with shirt #20 as a wing.
        $jbariExists = DB::table('players')
            ->where('first_name', 'Soufiane')
            ->where('last_name', 'Jbari')
            ->exists();

        if (! $jbariExists) {
            DB::table('players')->insert([
                'first_name' => 'Soufiane',
                'last_name' => 'Jbari',
                'photo' => null,
                'birthdate' => '2006-12-20',
                'position' => 'Ailier',
                'number' => 20,
                'nationality' => 'Marocaine',
                'height' => 178,
                'contract_until' => '2027-06-30',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            DB::table('players')
                ->where('first_name', 'Soufiane')
                ->where('last_name', 'Jbari')
                ->update([
                    'number' => 20,
                    'position' => 'Ailier',
                    'updated_at' => $now,
                ]);
        }

        // 3. Align positions with the FIXOS / WINGS / PIVOTS layout from the
        //    2026/27 squad visual. Wings and goalkeepers stay as they were.
        $updates = [
            ['Mohammed',     'Belqasmi',    'Pivot'],
            ['Majid',        'Dardar',      'Fixe'],
            ['El Mustapha',  'El Moula',    'Pivot'],
            ['Youness',      'Itiham',      'Fixe'],
            ['Mohammed',     'Jabrou',      'Fixe'],
        ];

        foreach ($updates as [$first, $last, $position]) {
            DB::table('players')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->update([
                    'position' => $position,
                    'updated_at' => $now,
                ]);
        }
    }

    public function down(): void
    {
        // Roster alignment is intentional; no rollback.
    }
};
