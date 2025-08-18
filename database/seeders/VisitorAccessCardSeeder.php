<?php

namespace Database\Seeders;

use App\Models\VisitorAccessCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitorAccessCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        VisitorAccessCard::factory()->count(20)->create();
    }
}
