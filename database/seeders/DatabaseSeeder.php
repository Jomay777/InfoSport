<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CardGeneration;
use App\Models\Category;
use App\Models\Club;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\GameStatistic;
use App\Models\PassRequest;
use App\Models\PhotoPlayer;
use App\Models\Pitch;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Models\PositionTable;
use App\Models\Team;
use App\Models\TeamSanction;
use App\Models\Tournament;
use App\Models\User;
use Database\Factories\GameSchedulingTeamFactory;
use Database\Factories\UserableFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       /*  $this->call([
          RoleSeeder::class,
          AdminSeeder::class,
          PitchSeeder::class
        ]); */
        

/*         User::factory(10)->create();

        Category::factory(10)->create();
        Club::factory(10)->create();

        Team::factory(10)->create();

        Player::factory(10)->create();
        CardGeneration::factory(10)->create();

        Tournament::factory(10)->create();

        //Pitch::factory(4)->create();
        GameRole::factory(10)->create();
        GameScheduling::factory(10)->create();
        Game::factory(9)->create();

       //GameSchedulingTeamFactory::new()->count(10)->create();

        //GameSchedulingTeamFactory::factory(10)->create();

        GameStatistic::factory(9)->create();
        PassRequest::factory(10)->create();
        PhotoPlayer::factory(10)->create();
        PlayerSanction::factory(10)->create();
        TeamSanction::factory(10)->create(); */
        PositionTable::factory(10)->create();
      //  UserableFactory::factory(10)->create();
        


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
