<?php

namespace Database\Factories;

use App\Models\VisitorAccessCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitorAccessCard>
 */
class VisitorAccessCardFactory extends Factory
{

    protected $model =VisitorAccessCard::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */    
    public function definition(): array
    {
        $active = $this->faker->randomElement(['enabled', 'disabled']);
        
        // If card is disabled, status must be unavailable
        // If card is enabled, status must be available
        $status = $active === 'disabled' ? 'unavailable' : 'available';

        return [
            'card_number' => $this->faker->unique()->numerify('PS-VS-####'),
            'status' => $status,
            'active' => $active,
        ];
    }
}
