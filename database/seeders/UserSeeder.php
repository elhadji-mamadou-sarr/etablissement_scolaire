<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrateur
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Principal',
            'email' => 'admin@ecole.sn',
            'password' => Hash::make('0000'),
            'role' => UserRole::ADMINISTRATEUR,
            'email_verified_at' => now(),
        ]);

        // Enseignant
        User::create([
            'nom' => 'Sokhna',
            'prenom' => 'Mbaye',
            'email' => 'enseignant@ecole.sn',
            'password' => Hash::make('0000'),
            'role' => UserRole::ENSEIGNANT,
            'email_verified_at' => now(),
        ]);

        // Élève/Parent
        User::create([
            'nom' => 'Mamadou',
            'prenom' => 'Ba',
            'email' => 'parent@ecole.sn',
            'password' => Hash::make('0000'),
            'role' => UserRole::ELEVE_PARENT,
            'email_verified_at' => now(),
        ]);
    }
}
