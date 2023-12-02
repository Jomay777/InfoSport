<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->realText(100),
            'club_id' => Club::all()->random()->id,
            'category_id' => Category::all()->random()->id,
        ];
    }
}
