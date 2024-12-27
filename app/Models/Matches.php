<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected  $table = 'matches';
    protected $fillable = [
        'id',
        'series_id',
        'description',
        'format',
        'start_date',
        'end_date',
        'status',
        'team1_id',
        'team2_id',
        'venue_id'
    ];

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function details()
    {
        return $this->hasOne(MatchDetail::class, 'match_id');
    }
}
