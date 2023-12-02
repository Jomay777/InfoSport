<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    //one-to-many inverse relationship 
    public function gameScheduling(){
        return $this->belongsTo(GameScheduling::class);
    }    

    //one-to-one relationship
    public function gameStatistic(){
        //$gameStatistic = GameStatistic::where('game_id', $this->id)->first();

        return $this->hasOne(GameStatistic::class);
    }
}
