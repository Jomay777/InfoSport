<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['first_name', 'second_name', 'last_name'
    ,'mother_last_name','gender' ,'birth_date' ,'c_i' ,'nacionality' ,'country_birth'
    ,'region_birth', 'state', 'team_id'];

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

    //one-to-one relationship
    public function photoPlayer(){
        return $this->hasOne(PhotoPlayer::class);
    }
    
    //one-to-many relationship
    public function playerSanctions(){
        return $this->hasMany(PlayerSanction::class);
    }
}
