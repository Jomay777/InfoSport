<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    //many-to-many realationship
    public function gameSchedulings(){
        return $this->belongsToMany(GameScheduling::class);
    }
    //one-to-many relationship
    public function players(){
        return $this->hasMany(Player::class);
    }
    //one-to-many inverse relationchip
    public function club(){
        return $this->belongsTo(Club::class);
    }    

    public function category(){
        return $this->belongsTo(Category::class);
    }    
}
