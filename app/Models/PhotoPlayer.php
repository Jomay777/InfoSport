<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PhotoPlayer extends Model
{
    protected $fillable = ['photo_path', 'photo_c_i', 'photo_birth_certificate',
    'photo_parental_authorization', 'player_id'];
    use HasFactory;

    //one-to-one inverse relationship
    public function player(){
        return $this->belongsTo(Player::class);
    }
}
