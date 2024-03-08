<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GameRole;
class GameScheduling extends Model
{
    use HasFactory;
    protected $fillable = ['time','game_role_id','team_a_id', 'team_b_id'];


    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }
    //many-to-many realationship
    /* public function teams(){
        return $this->belongsToMany(Team::class);
    } */

    //one-to-many inverse relationship
    public function gameRole()
    {
        return $this->belongsTo(GameRole::class);
    }

    //one-to-one relationship
    public function game(){
        return $this->hasOne(Game::class);
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

}
