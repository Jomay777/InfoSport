<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameStatistic extends Model
{
    use HasFactory;
    protected $fillable = ['goals_team_a', 'goals_team_b', 'yellow_cards_a', 'yellow_cards_b',
    'red_cards_a', 'red_cards_b', 'game_id'];

    //one-to-one inverse relationship
    public function game(){
        return $this->belongsTo(Game::class);
    }
}
