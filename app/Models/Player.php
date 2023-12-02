<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    //one-to-many inverse relationchip
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    //one-to-many relationship
    public function passRequests(){
        return $this->hasMany(PassRequest::class);
    }

    public function cardGenerations(){
        return $this->hasMany(CardGeneration::class);
    }

    //one-to-one relationship
    public function photoPlayer(){
        //$gameStatistic = GameStatistic::where('game_id', $this->id)->first();

        return $this->hasOne(PhotoPlayer::class);
    }
}
