<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $names = ['tech', 'business', 'hr', 'finance', 'audit'];

        $name = count($names) > 0
            ? array_shift($names)
            : 'generated-' . fake()->unique()->word();

        return [
            'name' => $name,
        ];
    }
}
