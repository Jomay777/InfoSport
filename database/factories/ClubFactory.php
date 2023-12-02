<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company(20);
        $coach = $this->faker->name(15);
        $logoPath = $this->faker->imageUrl(300, 300);

        return [
            'name' => $name,
            'coach'=> $coach,
            'logo_path'=> $logoPath
        ];
    }
}
