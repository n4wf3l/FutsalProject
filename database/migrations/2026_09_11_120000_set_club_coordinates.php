<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('club_info')) {
            return;
        }

        $row = DB::table('club_info')->first();
        $payload = [
            'latitude' => 34.2610000,
            'longitude' => -6.5802000,
            'updated_at' => Carbon::now(),
        ];

        if ($row === null) {
            DB::table('club_info')->insert(array_merge($payload, [
                'created_at' => Carbon::now(),
            ]));
            return;
        }

        DB::table('club_info')->where('id', $row->id)->update($payload);
    }

    public function down(): void
    {
        // Coordinate update is intentional; no rollback.
    }
};
