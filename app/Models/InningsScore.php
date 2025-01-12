<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InningsScore extends Model
{
    protected $fillable = [
        'series_id',
        'match_id',
        'inning_id',
        'team_id',
        'runs',
        'wickets',
        'overs',
        'inning_number'
    ];

    public function match()
    {
        return $this->belongsTo(MatchDetail::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
