<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassRequest extends Model
{
    use HasFactory;
    protected $fillable = ['request_photo_path', 'player_id'];
    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }    

    //one-to-many inverse relationchip
    public function player(){
        return $this->belongsTo(Player::class);
    }    
}
