<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        // 1. Remove the placeholder senior roster that existed before the
        //    real 2026/27 squad was seeded. Names come from the initial
        //    seed data; missing matches are a no-op.
        $departed = [
            ['Azzedine',    'Tale (C)'],
            ['Abdelbari',   'Khbaza'],
            ['Houssam',     'Rkich'],
            ['Tarik',       'Elmissaoui'],
            ['Anas',        'Sliki'],
            ['Issam',       'Moumen'],
            ['Mohamed',     'Sadiki'],
            ['Said',        'Tazete'],
            ['Soufiane',    'Lahlioui'],
            ['Ayoub',       'Hachhach'],
            ['Abdou',       'Hamdane'],
            ['Rachid',      'El Yassini'],
            ['Alaeddine',   'Rahoui'],
            ['Lahsen',      'El Harti'],
            ['Noureddine',  'Kabouchi'],
            // Player from the previous seed not in the 2026/27 shirt list
            ['Soufiane',    'Jbari'],
        ];

        foreach ($departed as [$first, $last]) {
            DB::table('players')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->delete();
        }

        // 2. Align current squad members to the shirt list from the club.
        //    Format: [first, last, number, position]. Position uses "Gardien"
        //    for goalkeepers so the Teams filter bucket catches them;
        //    outfield players stay "Ailier" for the same reason (fwd bucket).
        $roster = [
            // Goalkeepers
            ['Badreddine',   'Haddad',       1,  'Gardien'],
            ['Alae',         'Rahoui',       12, 'Gardien'],
            ['Zakaria',      'El Mouedene',  22, 'Gardien'],
            // Field players
            ['Ismail',       'Oukassou',     2,  'Ailier'],
            ['Mohammed',     'Belqasmi',     3,  'Ailier'],
            ['Elmahjoub',    'Halloumi',     4,  'Ailier'],
            ['Salah Eddine', 'Frihat',       5,  'Ailier'],
            ['Adam',         'Lamaizi',      6,  'Ailier'],
            ['Saifeddine',   'Bouyi',        7,  'Ailier'],
            ['Majid',        'Dardar',       8,  'Ailier'],
            ['El Mustapha',  'El Moula',     9,  'Ailier'],
            ['Youness',      'Itiham',       10, 'Ailier'],
            ['Adil',         'El Hamioui',   11, 'Ailier'],
            ['Mohammed',     'Jabrou',       14, 'Ailier'],
        ];

        foreach ($roster as [$first, $last, $number, $position]) {
            DB::table('players')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->update([
                    'number' => $number,
                    'position' => $position,
                    'updated_at' => $now,
                ]);
        }

        // 3. New goalkeeper Reda joins with #16. Full name and birthdate are
        //    placeholders since the club only provided the first name.
        //    Admin can complete via /players/{id}/edit.
        $redaExists = DB::table('players')
            ->where('first_name', 'Reda')
            ->where('number', 16)
            ->exists();

        if (! $redaExists) {
            DB::table('players')->insert([
                'first_name' => 'Reda',
                'last_name' => 'À compléter',
                'photo' => null,
                'birthdate' => '2000-01-01',
                'position' => 'Gardien',
                'number' => 16,
                'nationality' => 'Marocaine',
                'height' => 178,
                'contract_until' => '2027-06-30',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 4. Azzedine Tale moves from the pitch to the bench as goalkeeper
        //    coach. Birthdate 14/07/1989 not stored (staff table has no
        //    birthdate column; adding one is out of scope for this change).
        $coachExists = DB::table('staff')
            ->where('first_name', 'Azzedine')
            ->where('last_name', 'Tale')
            ->exists();

        if (! $coachExists) {
            DB::table('staff')->insert([
                'first_name' => 'Azzedine',
                'last_name' => 'Tale',
                'position' => 'Entraîneur des gardiens',
                'photo' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Data curation is intentional; no rollback.
    }
};
