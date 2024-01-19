<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRole extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'date', 'tournament_id', 'pitch_id'];

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }
    //one-to-many relationship
    public function gameSchedulings(){
        return $this->hasMany(GameScheduling::class);
    }
    //one-to-many inverse relationship
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
