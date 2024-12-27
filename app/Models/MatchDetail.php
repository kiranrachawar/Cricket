<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'match_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_id',
        'type',
        'complete',
        'domestic',
        'day_night',
        'year',
        'day_number',
        'state',
        'status',
        'toss_winner',
        'decision',
        'winning_team',
        'winning_margin',
        'win_by_runs',
        'win_by_innings',
    ];

    /**
     * Get the match that owns the details.
     */
    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }
}
