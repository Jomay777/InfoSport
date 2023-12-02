<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRole extends Model
{
    use HasFactory;

    //one-to-many relationship
    public function gameSchedulings(){
        return $this->hasMany(GameScheduling::class);
    }
    //one-to-many inverse relationship
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
