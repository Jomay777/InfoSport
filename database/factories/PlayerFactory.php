<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'second_name' => $this->faker->optional()->firstName,
            'last_name' => $this->faker->lastName,
            'mother_last_name' => $this->faker->optional()->lastName,
            'birth_date' => $this->faker->date,
            'c_i' => $this->faker->unique()->randomNumber(8),
            'nacionality' => $this->faker->randomElement(['Venezolana', 'Colombiana', 'Boliviana', 'Argentina']),
            'country_birth' => $this->faker->randomElement(['Venezuela', 'Colombia', 'Bolivia', 'Argentina']),
            'region_birth' => $this->faker->city,
            'state' => $this->faker->randomElement([1, 2]),
            'team_id' => Team::all()->random()->id,
        ];
    }
}
