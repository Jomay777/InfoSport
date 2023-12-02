<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }    

    //one-to-many relationship
    public function tournaments(){
        return $this->hasMany(Tournament::class);
    }
    //one-to-many relationship
    public function teams(){
        return $this->hasMany(Team::class);
    }

}
