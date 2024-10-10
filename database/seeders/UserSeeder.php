<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Criar um usuário padrão com papel 'Master'
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Use uma senha segura em produção
        ]);

        $user->assignRole('Master');
    }
}
