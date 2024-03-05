<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Club;
use App\Models\Category;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'club_id', 'category_id'];


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
    //one-to-many relationship
    public function teamSanctions(){
        return $this->hasMany(TeamSanction::class);
    }
}
