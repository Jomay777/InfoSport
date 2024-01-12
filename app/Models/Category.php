<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\User;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];


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
