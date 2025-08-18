<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        // Generate 50 employees assigned to the 5 pre-created departments

        Employee::create([
            'employee_number' => 'PS-TECH-2039',
            'access_card_number'=> 'PS-4093',
            'first_name'=>'Korkoe',
            'other_name'=>'Anthony Kwami',
            'last_name'=>'Dumashie',
            'email'=>'korkoedumashie@gmail.com',
            'phone_number'=>'233558731186',
            'department_id'=>1,
            'job_title'=>'Software Engineer',
            'gender'=>'male'
        ]);
        Employee::factory()->count(10)->create();
    }
}
