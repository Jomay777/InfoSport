<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSanction extends Model
{
    use HasFactory;
    protected $fillable = ['yellow_cards', 'red_card','state', 'sanction', 'game_id', 'player_id'];

    //one-to-one inverse relationship 
    public function game(){
        return $this->belongsTo(Game::class);
    }    
    //one-to-one inverse relationship 
    public function player(){
        return $this->belongsTo(Player::class);
    }    


}
