<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\User;
use App\Models\GameRole;

class Tournament extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'category_id'];


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
    //method for get tournaments published
    public function scopePublished($query)
    {
        return $query->where('state', 'Publicado');
    }
}
