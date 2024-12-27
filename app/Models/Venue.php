<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['id', 'ground', 'city', 'timezone'];

    public function matches()
    {
        return $this->hasMany(MatchDetail::class, 'venue_id');
    }
}

