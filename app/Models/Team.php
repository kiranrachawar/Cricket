<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['id', 'name', 'short_name', 'image_id'];

    public function matchesAsTeam1()
    {
        return $this->hasMany(MatchDetail::class, 'team1_id');
    }

    public function matchesAsTeam2()
    {
        return $this->hasMany(MatchDetail::class, 'team2_id');
    }
}

