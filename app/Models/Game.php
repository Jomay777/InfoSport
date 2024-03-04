<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['result',
    'observation',
    'game_scheduling_id'];

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    //one-to-one inverse relationship 
    public function gameScheduling(){
        return $this->belongsTo(GameScheduling::class);
    }    

    //one-to-one relationship
    public function gameStatistic(){
        //$gameStatistic = GameStatistic::where('game_id', $this->id)->first();

        return $this->hasOne(GameStatistic::class);
    }

    //one-to-many relationship
    public function playerSanctions(){
        return $this->hasMany(PlayerSanction::class);
    }
}
