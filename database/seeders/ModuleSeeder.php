<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'name'=>'staff',
            'description'=>'permissions for modifying employee information'
        ]);
        Module::create([
            'name'=>'keys',
            'description'=>'permissions for managing keys'
        ]);
        Module::create([
            'name'=>'settings',
            'description'=>'permissions for modifying settings'
        ]);
        Module::create([
            'name'=>'logs',
            'description'=>'permissions for accessing logs'
        ]);
        Module::create([
            'name'=>'visits',
            'description'=>'permissions for modifying visitor information'
        ]);
        Module::create([
            'name'=>'roles',
            'description'=>'permissions for modifying roles andpermissions'
        ]);
        Module::create([
            'name'=>'user',
            'description'=>'permissions for modifying user information'
        ]);
        Module::create([
            'name'=>'reports',
            'description'=>'permissions for modifying reports'
        ]);
        Module::create([
            'name'=>'departments',
            'description'=>'permissions for modifying department information'
        ]);
    }


}
