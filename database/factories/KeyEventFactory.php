<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Key;
use App\Models\KeyEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeyEvent>
 */
class KeyEventFactory extends Factory
{


    protected $model = KeyEvent::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            
            'picked_by' => Employee::inRandomOrder()->first()->id,
            'returned_by' => Employee::inRandomOrder()->first()->id,
            'key_number' => Key::inRandomOrder()->first()->key_number,
            'status' => fake()->randomElement(['picked', 'returned']),
            'picked_at' => fake()->dateTimeBetween('-1 days', 'now'),
            'returned_at' => fake()->dateTimeBetween('-1 days', '+3 days'),

        ];
    }
}
