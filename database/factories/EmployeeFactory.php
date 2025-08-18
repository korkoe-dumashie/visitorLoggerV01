<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->numerify('EMP###'),
            'access_card_number' => fake()->unique()->numerify('AC###'),
            'first_name' => fake()->firstName(),
            'other_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'employment_status'=>'active',
            'phone_number' => fake()->phoneNumber(),
            'department_id' => Department::inRandomOrder()->first()->id, // Assign an existing department
            'job_title' => fake()->jobTitle(),
            'gender' => fake()->randomElement(['male', 'female']),
        ];
    }
}
