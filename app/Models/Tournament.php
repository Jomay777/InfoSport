<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    //polymorphic many-to-many relationship
    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    //one-to-many relationship
    public function gameRoles()
    {
        return $this->hasMany(GameRole::class);
    }
    //one-to-many inverse relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
