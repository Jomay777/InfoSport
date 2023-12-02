<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameScheduling extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }
    //many-to-many realationship
    public function teams(){
        return $this->belongsToMany(Team::class);
    }

    //one-to-many inverse relationship
    public function gameRole()
    {
        return $this->belongsTo(GameRole::class);
    }

     //one-to-many relationship
     public function games(){
        return $this->hasMany(Game::class);
    }
}
