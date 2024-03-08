<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionTable extends Model
{
    use HasFactory; 
    protected $fillable = ['points', 'games_played', 'games_won', 'games_drawn', 'games_lost', 'goals_scored', 'goals_against', 'tournament_id', 'team_id',];

    //one-to-many inverse relationship
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    //one-to-many inverse relationship
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
