<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Club extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coach', 'logo_path'];

    //polymorphic many-to-many relationship
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'userable');
    }

    //one-to-many relationship
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
    
}
