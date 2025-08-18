<?php

namespace Database\Seeders;

use App\Models\KeyEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeyEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KeyEvent::factory()->count(10)->create();
    }
}
