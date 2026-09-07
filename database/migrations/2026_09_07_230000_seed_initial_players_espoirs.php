<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        $players = [
            ['number' => 1,  'first_name' => 'Rida',      'last_name' => 'El Akhal',   'birthdate' => '2007-03-29', 'height' => 175],
            ['number' => 2,  'first_name' => 'Saad',      'last_name' => 'Bouiba',     'birthdate' => '2008-07-05', 'height' => 180],
            ['number' => 3,  'first_name' => 'Ahmed',     'last_name' => 'Syaghi',     'birthdate' => '2007-01-31', 'height' => 178],
            ['number' => 4,  'first_name' => 'Soufiane',  'last_name' => 'Aziz',       'birthdate' => '2007-06-14', 'height' => 172],
            ['number' => 5,  'first_name' => 'Islam',     'last_name' => 'Lassiri',    'birthdate' => '2007-12-14', 'height' => 183],
            ['number' => 6,  'first_name' => 'Adam',      'last_name' => 'El Khatib',  'birthdate' => '2008-01-02', 'height' => 176],
            ['number' => 7,  'first_name' => 'Marouane',  'last_name' => 'Chahmat',    'birthdate' => '2007-01-11', 'height' => 179],
            ['number' => 8,  'first_name' => 'Achraf',    'last_name' => 'Zontal',     'birthdate' => '2007-03-25', 'height' => 185],
            ['number' => 9,  'first_name' => 'Bilal',     'last_name' => 'Hadada',     'birthdate' => '2007-11-11', 'height' => 174],
            ['number' => 10, 'first_name' => 'Riyad',     'last_name' => 'Ijaamame',   'birthdate' => '2007-11-28', 'height' => 181],
            ['number' => 11, 'first_name' => 'Saad',      'last_name' => 'Asli',       'birthdate' => '2008-04-30', 'height' => 177],
            ['number' => 12, 'first_name' => 'Mohammed',  'last_name' => 'Belarbi',    'birthdate' => '2008-07-04', 'height' => 186],
            ['number' => 13, 'first_name' => 'Ahmed',     'last_name' => 'Obaiss',     'birthdate' => '2008-07-22', 'height' => 173],
            ['number' => 14, 'first_name' => 'Zakaria',   'last_name' => 'Bouhlassa',  'birthdate' => '2008-08-13', 'height' => 180],
            ['number' => 15, 'first_name' => 'Mustapha',  'last_name' => 'Cherradi',   'birthdate' => '2008-09-16', 'height' => 178],
            ['number' => 16, 'first_name' => 'Ali',       'last_name' => 'Firdaous',   'birthdate' => '2008-09-25', 'height' => 182],
        ];

        foreach ($players as $p) {
            $exists = DB::table('players_espoirs')
                ->where('first_name', $p['first_name'])
                ->where('last_name', $p['last_name'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('players_espoirs')->insert([
                'first_name' => $p['first_name'],
                'last_name' => $p['last_name'],
                'photo' => null,
                'birthdate' => $p['birthdate'],
                'position' => 'Ailier',
                'number' => $p['number'],
                'nationality' => 'Marocaine',
                'height' => $p['height'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $names = [
            ['Rida', 'El Akhal'], ['Saad', 'Bouiba'], ['Ahmed', 'Syaghi'], ['Soufiane', 'Aziz'],
            ['Islam', 'Lassiri'], ['Adam', 'El Khatib'], ['Marouane', 'Chahmat'], ['Achraf', 'Zontal'],
            ['Bilal', 'Hadada'], ['Riyad', 'Ijaamame'], ['Saad', 'Asli'], ['Mohammed', 'Belarbi'],
            ['Ahmed', 'Obaiss'], ['Zakaria', 'Bouhlassa'], ['Mustapha', 'Cherradi'], ['Ali', 'Firdaous'],
        ];

        foreach ($names as [$first, $last]) {
            DB::table('players_espoirs')
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->delete();
        }
    }
};
