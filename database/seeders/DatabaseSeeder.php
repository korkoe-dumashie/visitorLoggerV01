<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call other seeders
        $this->call([
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            KeySeeder::class,
            VisitorAccessCardSeeder::class,
            ModuleSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            PermissionsSeeder::class
        ]);

    }

}