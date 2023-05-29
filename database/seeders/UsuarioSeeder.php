<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => "Administrador",
            'email'     => "admin@gmail.com",
            'password'  => bcrypt("Clave.1234"),
            'created_at'=> date("Y-m-d H:i:s")
        ])->assignRole('Administrador');

        User::create([
            'name'      => "Editor",
            'email'     => "editor@gmail.com",
            'password'  => bcrypt("Clave.1234"),
            'created_at'=> date("Y-m-d H:i:s")
        ])->assignRole('Editor');
    }
}
