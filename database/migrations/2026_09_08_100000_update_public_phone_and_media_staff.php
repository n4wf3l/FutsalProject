<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('club_info')->update([
            'phone' => '+212 603-494414',
            'updated_at' => Carbon::now(),
        ]);

        // Previous media contact leaves, new one joins.
        DB::table('staff')
            ->where('first_name', 'Mehdi')
            ->where('last_name', 'El Kahlaoui')
            ->delete();

        $exists = DB::table('staff')
            ->where('first_name', 'Ayoub')
            ->where('last_name', 'El Hachmi')
            ->exists();

        if (! $exists) {
            DB::table('staff')->insert([
                'first_name' => 'Ayoub',
                'last_name' => 'El Hachmi',
                'position' => 'Communication and media',
                'photo' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    public function down(): void
    {
        // Contact update is intentional; no rollback.
    }
};
