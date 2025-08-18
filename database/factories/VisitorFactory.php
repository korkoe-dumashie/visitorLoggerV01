<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{

    protected $model = Visitor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'first_name' => fake()->firstName(),
            // // 'other_name' => fake()->firstName(),
            // 'last_name' => fake()->lastName(),
            'full_name'=>fake()->name(),
            'email' => fake()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'employee_Id' => Employee::inRandomOrder()->first()->id,
            'company_name' => fake()->company(),
            // 'access_card_number' => fake()->randomNumber(8),
            'purpose' => fake()->randomElement(['interview', 'personal', 'official', 'other']),
            'status' => fake()->randomElement(['ongoing', 'departed']),
            // 'marketing_consent' => fake()->boolean(),
            'devices' => [
                'name'=>fake()->randomElement(['HP','Dell','Toshiba']),
                'serial'=>fake()->numerify('ICUU#######')
            ],
            'companions' => [
                'name'=>fake()->name(),
                'phone_number'=>fake()->phoneNumber()
            ],
        ];
    }
}
