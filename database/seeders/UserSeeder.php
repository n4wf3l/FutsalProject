<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        User::create([
            'name' => 'Abderrahmane Gayedi',
            'email' => 'a.gayedi@dkfc.ma',
            'password' => Hash::make('a.gayedi7789'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Mouad Benallal',
            'email' => 'm.benallal@dkfc.ma',
            'password' => Hash::make('m.benallal6655'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Karim Bouabdeli',
            'email' => 'k.bouabdeli@dkfc.ma',
            'password' => Hash::make('k.bouabdeli4472'),
            'email_verified_at' => now(),
        ]);
    }
}
