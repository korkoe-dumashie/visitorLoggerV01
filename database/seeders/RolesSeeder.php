<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::create([
            'name' => 'admin',
            'description'=> 'Has full access'
        ]);
        Roles::create([
            'name' => 'hr',
            'description'=> 'has access to daily events'
        ]);
        Roles::create([
            'name' => 'security',
            'description'=> 'has access to basic features'
        ]);
        Roles::create([
            'name' => 'support',
            'description'=> 'has access to logs and reports'
        ]);
        Roles::create([
            'name' => 'visitor',
            'description'=> 'can log visit and exits'
        ]);
    }
}
