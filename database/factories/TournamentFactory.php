<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'state' => $this->faker->randomElement(['Publicado', 'No publicado']),
            'description' => $this->faker->realText(490),
            'category_id' => $this->faker->boolean ? Category::all()->random()->id : null,
        ];
    }
}
