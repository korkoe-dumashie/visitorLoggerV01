<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Predefine the departments
        $departments = ['tech', 'business', 'hr', 'finance', 'audit'];

        foreach ($departments as $departmentName) {
            Department::firstOrCreate(['name' => $departmentName]);
        }
    }
}
