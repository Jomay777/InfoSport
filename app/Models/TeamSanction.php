<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSanction extends Model
{
    use HasFactory;
    protected $fillable = ['observation','state', 'sanction', 'game_id', 'team_id'];

    //one-to-one inverse relationship 
    public function game(){
        return $this->belongsTo(Game::class);
    }    
    //one-to-one inverse relationship 
    public function team(){
        return $this->belongsTo(Team::class);
    }    
}
