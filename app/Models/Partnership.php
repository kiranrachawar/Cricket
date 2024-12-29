<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'match_id',
        'bat1_name',
        'bat2_name',
        'total_runs',
        'total_balls'
    ];
}
