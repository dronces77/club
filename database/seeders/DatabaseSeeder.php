<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seeders existentes
        $this->call([
            AdminSeeder::class,
            RolePermissionSeeder::class,
            // Agregar el nuevo seeder de modalidades
            ModalidadesSeeder::class,
        ]);
    }
}