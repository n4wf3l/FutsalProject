<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seasons')) {
            return;
        }

        $now = Carbon::now();

        // Sourced from the Wikipedia FR article on Dina Kenitra Futsal Club.
        // Idempotent on season_label so re-running does nothing.
        $seasons = [
            [
                'season_label' => '2010-2011', 'season_start_year' => 2010,
                'division' => 'Ligue du Gharb', 'position' => 6, 'position_label' => null,
                'cup_result' => null, 'coach' => 'Mohammed Bensaid', 'badge' => null,
                'notes' => "Première saison du club, effectif jeune formé à Kénitra.",
            ],
            [
                'season_label' => '2011-2012', 'season_start_year' => 2011,
                'division' => 'Ligue du Gharb', 'position' => 5, 'position_label' => null,
                'cup_result' => null, 'coach' => 'Mohammed Bensaid', 'badge' => null,
                'notes' => null,
            ],
            [
                'season_label' => '2012-2013', 'season_start_year' => 2012,
                'division' => 'Ligue du Gharb', 'position' => null, 'position_label' => null,
                'cup_result' => null, 'coach' => 'Bensaid puis Touim', 'badge' => null,
                'notes' => "Année de transition sportive et institutionnelle.",
            ],
            [
                'season_label' => '2013-2014', 'season_start_year' => 2013,
                'division' => 'Ligue du Gharb', 'position' => 1, 'position_label' => 'Champion',
                'cup_result' => null, 'coach' => 'Adil Touim', 'badge' => 'Champion',
                'notes' => "Titre régional, montée en Division 2 nationale acquise.",
            ],
            [
                'season_label' => '2014-2015', 'season_start_year' => 2014,
                'division' => 'D2', 'position' => 1, 'position_label' => 'Champion',
                'cup_result' => null, 'coach' => 'Driss Talmoust', 'badge' => 'Champion',
                'notes' => "Champion dès la première saison en D2, promotion en Futsal D1.",
            ],
            [
                'season_label' => '2015-2016', 'season_start_year' => 2015,
                'division' => 'D1', 'position' => 5, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => "Belle entame en élite, 5e sur 12.",
            ],
            [
                'season_label' => '2016-2017', 'season_start_year' => 2016,
                'division' => 'D1', 'position' => 8, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => "Départs marquants de Boumezou et Nessis.",
            ],
            [
                'season_label' => '2017-2018', 'season_start_year' => 2017,
                'division' => 'D1', 'position' => 11, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => 'Relégué',
                'notes' => "Relégation en Division 2.",
            ],
            [
                'season_label' => '2018-2019', 'season_start_year' => 2018,
                'division' => 'D2', 'position' => 2, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => 'Vice-champion',
                'notes' => "Vice-champion, promotion manquée de peu.",
            ],
            [
                'season_label' => '2019-2020', 'season_start_year' => 2019,
                'division' => 'D2', 'position' => 2, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => 'Vice-champion',
                'notes' => "Deuxième vice-championnat consécutif, retraite d'Otman Idel-Aouad.",
            ],
            [
                'season_label' => '2020-2021', 'season_start_year' => 2020,
                'division' => 'D1', 'position' => 3, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => 'Promu',
                'notes' => "Retour en D1 suite à la restructuration FRMF. Soufiane Lahlioui meilleur buteur (40 buts).",
            ],
            [
                'season_label' => '2021-2022', 'season_start_year' => 2021,
                'division' => 'D1', 'position' => 4, 'position_label' => null,
                'cup_result' => 'Annulée', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => "Passage de la D1 à 16 clubs, 4e place.",
            ],
            [
                'season_label' => '2022-2023', 'season_start_year' => 2022,
                'division' => 'D1', 'position' => 5, 'position_label' => null,
                'cup_result' => 'Demi-finale', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => "Demi-finale de la Coupe du Trône, défaite 5-2 face au Faucon d'Agadir.",
            ],
            [
                'season_label' => '2023-2024', 'season_start_year' => 2023,
                'division' => 'D1', 'position' => 11, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => null,
            ],
            [
                'season_label' => '2024-2025', 'season_start_year' => 2024,
                'division' => 'D1', 'position' => 10, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => null,
                'notes' => null,
            ],
            [
                'season_label' => '2025-2026', 'season_start_year' => 2025,
                'division' => 'D1', 'position' => 15, 'position_label' => null,
                'cup_result' => '16e tour', 'coach' => 'Driss Talmoust', 'badge' => 'Relégué',
                'notes' => "Saison compliquée, relégation en D2.",
            ],
            [
                'season_label' => '2026-2027', 'season_start_year' => 2026,
                'division' => 'D2', 'position' => null, 'position_label' => 'En cours',
                'cup_result' => 'En cours', 'coach' => 'Driss Talmoust', 'badge' => 'En cours',
                'notes' => "Nouveau cycle avec un effectif rajeuni.",
            ],
        ];

        foreach ($seasons as $s) {
            $exists = DB::table('seasons')
                ->where('season_label', $s['season_label'])
                ->exists();
            if ($exists) {
                continue;
            }
            DB::table('seasons')->insert(array_merge($s, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        // Historical seed is intentional; no rollback.
    }
};
