<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CricketSeries extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default pluralized form
    protected $table = 'cricket_series';

    // Specify the fillable fields
    protected $fillable = [
        'series_id',
        'series_date',
        'name',
        'start_date',
        'end_date',
        'is_fantasy_handbook_enabled',
    ];

    // Specify any casting for the attributes
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_fantasy_handbook_enabled' => 'boolean',
    ];

    // Relationships
    public function matches()
    {
        return $this->hasMany(Matches::class, 'series_id', 'id');
    }
}
