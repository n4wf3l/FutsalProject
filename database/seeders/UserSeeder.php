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
            'name' => 'Test User',          // Change avec le nom que tu veux
            'email' => 'nawfel@hotmail.com',  // Change avec l'email que tu veux
            'password' => Hash::make('Nawfel123.'),  // Change avec le mot de passe que tu veux
            'email_verified_at' => now(),  // Optionnel : marquer le compte comme vérifié
        ]);
    }
}
