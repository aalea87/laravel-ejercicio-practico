<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roleAdmin   = Role::create(['name' => 'Administrador']);
        $roleEditor  = Role::create(['name' => 'Editor']);
        $roleCliente = Role::create(['name' => 'Cliente']);

        Permission::create(['name' => 'producto_read'])->syncRoles([$roleAdmin,$roleEditor,$roleCliente]);
        Permission::create(['name' => 'producto_create'])->syncRoles([$roleAdmin,$roleEditor]);
        Permission::create(['name' => 'producto_edit'])->syncRoles([$roleAdmin,$roleEditor]);
        Permission::create(['name' => 'producto_delete'])->syncRoles([$roleAdmin,$roleEditor]);
    }
}
