<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Club;
use App\Models\Category;
use App\Models\Game;
use App\Models\GameScheduling;
use App\Models\PassRequest;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //polymorphic inverse many-to-many relationship

    public function clubs(): MorphToMany
    {
        return $this->morphedByMany(Club::class, 'userable');
    }
    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'userable');        
    }
    public function games(): MorphToMany
    {
        return $this->morphedByMany(Game::class, 'userable');
    }
    public function gameschedulings(): MorphToMany
    {
        return $this->morphedByMany(GameScheduling::class, 'userable');
    }
    public function passrequests(): MorphToMany
    {
        return $this->morphedByMany(PassRequest::class, 'userable');
    }
    public function players(): MorphToMany
    {
        return $this->morphedByMany(Player::class, 'userable');
    }
    public function teams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'userable');
    }
    public function tournaments(): MorphToMany
    {
        return $this->morphedByMany(Tournament::class, 'userable');
    }
    public function gameRoles(): MorphToMany
    {
        return $this->morphedByMany(GameRole::class, 'userable');
    }

    //one-to-many relationship
    public function cardGenerations(){
        return $this->hasMany(CardGeneration::class);
    }
}
