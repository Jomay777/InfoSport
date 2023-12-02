<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Club;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\PassRequest;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
    public function definition(): array
    {
        $userableType = $this->faker->randomElement(
            [
                'App\Models\Club',
                'App\Models\Category',
                'App\Models\Game',
                'App\Models\GameRole',
                'App\Models\GameScheduling',
                'App\Models\PassRequest',
                'App\Models\Player',
                'App\Models\Team',
                'App\Models\Tournamet',
            ]
        );
        $userable = $this->faker->randomElement([
            User::all()->random()->id,
            Club::all()->random()->id,
            Category::all()->random()->id,
            Game::all()->random()->id,
            GameRole::all()->random()->id,
            GameScheduling::all()->random()->id,
            PassRequest::all()->random()->id,
            Player::all()->random()->id,
            Team::all()->random()->id,
            Tournament::all()->random()->id,
        ]);
        return [
            'userable_id' => $userable->id,
            'userable_type' => $userableType,
            'user_id' => User::all()->random()->id,
        ];
    }
}
