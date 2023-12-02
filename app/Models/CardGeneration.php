<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardGeneration extends Model
{
    use HasFactory;
    
    //one-to-many inverse relationship
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function player(){
        return $this->belongsTo(Player::class);
    }
}
