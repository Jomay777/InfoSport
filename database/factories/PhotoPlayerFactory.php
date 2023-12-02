<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotoPlayer>
 */
class PhotoPlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $player = Player::factory()->create();
        return [
            'photo_path' => $this->faker->imageUrl(300, 300),
            'photo_c_i' => $this->faker->imageUrl(300, 300),
            'photo_birth_certificate' => $this->faker->imageUrl(300, 300),
            'photo_parental_authorization' => $this->faker->imageUrl(300, 300),
            'player_id' => $player->id,
        ];
    }
}
