<?php

// app/Repositories/CricketSeriesRepository.php
namespace App\Repositories;

use App\Interfaces\CricketSeriesRepositoryInterface;
use App\Models\CricketSeries;
use App\Models\Matches;
use App\Models\Team;
use App\Models\Venue;

class CricketSeriesRepository implements CricketSeriesRepositoryInterface
{
    protected $model;

    public function __construct(CricketSeries $model)
    {
        $this->model = $model;
    }

    public function get_all_series()
    {
        $data = CricketSeries::all();

        return $data;
    }



    public function get_series_data($post_data)
    {
        $post_data['series_id'] = 7745;
        $data = CricketSeries::where('series_id', $post_data['series_id'])->get();

        return $data;
    }


    public function get_all_match_data()
    {
        $matches = Matches::all();

        return $matches;
    }

    public function get_venues()
    {
        $venues = Venue::all();

        return $venues;
    }

    public function get_teams()
    {
        $teams = Team::all();

        return $teams;
    }
}
