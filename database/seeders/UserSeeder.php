<?php

namespace Database\Seeders;

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
        User::create([
            'name' => 'Admin',
            'role_id'=>1,
            'email' => 'admin',
            'password' => Hash::make('admin'),
        ]);
        User::create([
            'name' => 'Abigail',
            'role_id'=>2,
            'email' => 'hr@payswitch.com.gh',
            'password' => Hash::make('123212321'),
        ]);
        User::create([
            'name' => 'Solomon',
            'role_id'=>3,
            'email' => 'security@payswitch.com.gh',
            'password' => Hash::make('123212321'),
        ]);
        User::create([
            'name' => 'Nana',
            'role_id'=>4,
            'email' => 'support@payswitch.com.gh',
            'password' => Hash::make('123212321'),
        ]);
        User::create([
            'name' => 'Visitor',
            'role_id'=>5,
            'username' => 'visit',
            'password' => Hash::make('visit'),
        ]);
    }
}