<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>User::inRandomOrder()->first()->id ?? User::factory(),
            'module_id'=>Module::inRandomOrder()->first()->id ?? Module::factory(),
            'can_view'=>fake()->boolean(),
            'can_create'=>fake()->boolean(),
            'can_modify'=>fake()->boolean(),
            'can_delete'=>fake()->boolean(),

        ];
    }
}
