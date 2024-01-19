<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    //relationship one to many
    public function gameRoles(){
        return $this->hasMany(GameRole::class);
    }

}
