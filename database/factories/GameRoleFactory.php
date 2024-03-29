<?php

namespace Database\Factories;

use App\Models\Pitch;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameRole>
 */
class GameRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'date' => $this->faker->date,
            'tournament_id' => Tournament::all()->random()->id,
            'pitch_id' => Pitch::all()->random()->id,
        ];
    }
}
