<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PhotoPlayer extends Model
{
    use HasFactory;

    //one-to-one inverse relationship
    public function player(){
        return $this->belongsTo(Player::class);
    }
}
