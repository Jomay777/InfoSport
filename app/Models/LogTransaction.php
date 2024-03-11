<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['action',
    'resource',
    'resource_id',
    'details',
    'user_id',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
