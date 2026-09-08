<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();
        $contractUntil = '2027-06-30';

        $players = [
            ['number' => 1,  'first_name' => 'Badreddine',   'last_name' => 'Haddad',       'birthdate' => '2003-04-18', 'height' => 178],
            ['number' => 2,  'first_name' => 'Alae',         'last_name' => 'Rahoui',       'birthdate' => '2004-10-05', 'height' => 175],
            ['number' => 3,  'first_name' => 'Zakaria',      'last_name' => 'El Mouedene',  'birthdate' => '2006-06-28', 'height' => 180],
            ['number' => 4,  'first_name' => 'Majid',        'last_name' => 'Dardar',       'birthdate' => '1999-01-03', 'height' => 183],
            ['number' => 5,  'first_name' => 'Elmahjoub',    'last_name' => 'Halloumi',     'birthdate' => '2002-11-13', 'height' => 176],
            ['number' => 6,  'first_name' => 'Mohammed',     'last_name' => 'Belqasmi',     'birthdate' => '2003-06-18', 'height' => 181],
            ['number' => 7,  'first_name' => 'Adil',         'last_name' => 'El Hamioui',   'birthdate' => '2003-09-14', 'height' => 174],
            ['number' => 8,  'first_name' => 'Salah Eddine', 'last_name' => 'Frihat',       'birthdate' => '2004-02-07', 'height' => 179],
            ['number' => 9,  'first_name' => 'Saifeddine',   'last_name' => 'Bouyi',        'birthdate' => '2004-04-07', 'height' => 172],
            ['number' => 10, 'first_name' => 'Youness',      'last_name' => 'Itiham',       'birthdate' => '2004-06-01', 'height' => 185],
            ['number' => 11, 'first_name' => 'El Mustapha',  'last_name' => 'El Moula',     'birthdate' => '2005-09-06', 'height' => 177],
            ['number' => 12, 'first_name' => 'Mohammed',     'last_name' => 'Jabrou',       'birthdate' => '2005-12-23', 'height' => 182],
            ['number' => 13, 'first_name' => 'Ismail',       'last_name' => 'Oukassou',     'birthdate' => '2006-10-20', 'height' => 173],
            ['number' => 14, 'first_name' => 'Soufiane',     'last_name' => 'Jbari',        'birthdate' => '2006-12-20', 'height' => 186],
            ['number' => 15, 'first_name' => 'Adam',         'last_name' => 'Lamaizi',      'birthdate' => '2006-12-21', 'height' => 178],
        ];

        foreach ($players as $p) {
            $exists = DB::table('players')
                ->where('first_name', $p['first_name'])
                ->where('last_name', $p['last_name'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('players')->insert([
                'first_name' => $p['first_name'],
                'last_name' => $p['last_name'],
                'photo' => null,
                'birthdate' => $p['birthdate'],
                'position' => 'Ailier',
                'number' => $p['number'],
                'nationality' => 'Marocaine',
                'height' => $p['height'],
                'contract_until' => $contractUntil,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $names = [
            ['Badreddine', 'Haddad'], ['Alae', 'Rahoui'], ['Zakaria', 'El Mouedene'],
            ['Majid', 'Dardar'], ['Elmahjoub', 'Halloumi'], ['Mohammed', 'Belqasmi'],
            ['Adil', 'El Hamioui'], ['Salah Eddine', 'Frihat'], ['Saifeddine', 'Bouyi'],
            ['Youness', 'Itiham'], ['El Mustapha', 'El Moula'], ['Mohammed', 'Jabrou'],
            ['Ismail', 'Oukassou'], ['Soufiane', 'Jbari'], ['Adam', 'Lamaizi'],
        ];

        foreach ($names as [$first, $last]) {
            DB::table('players')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->delete();
        }
    }
};
