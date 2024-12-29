<?php

// app/Repositories/CricketSeriesRepository.php
namespace App\Repositories;

use App\Interfaces\CricketSeriesRepositoryInterface;
use App\Models\Batsmen;
use App\Models\Bowler;
use App\Models\CricketSeries;
use App\Models\InningsScore;
use App\Models\Matches;
use App\Models\Partnership;
use App\Models\PlayerOfMatch;
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
        $series_id = $post_data['series_id'];

        $data = CricketSeries::where('series_id', $series_id)->get();

        return $data;
    }


    public function get_matche_data($post_data)
    {
        $series_id = $post_data['series_id'];
        $matches = Matches::where('series_id', $series_id)->get();

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

    public function get_innings($post_data)
    {
        //$match_id = 91778;
        $match_id = $post_data['match_id'];
        // $innings = InningsScore::where('match_id', $match_id)->get();
        $innings = InningsScore::select(
            'innings_scores.*',
            'teams.name as team_name'
        )
            ->join('teams', 'innings_scores.team_id', '=', 'teams.id')
            ->where('innings_scores.match_id',  $match_id)
            ->get();

        return $innings;
    }

    public function partnership_details($post_data)
    {
        $inning_id = $post_data['inning_id'];
        $match_id = $post_data['match_id'];

        $partnership = Partnership::where([
            ['match_id' => $match_id],
            ['inning_id' => $inning_id]
        ])->get();

        return $partnership;
    }


    public function player_of_match()
    {
        $match_id = 91778;

        $player_of_match = PlayerOfMatch::where('match_id', $match_id)->first();

        return $player_of_match;
    }

    public function get_inning_details($post_data)
    {
        $inning_id = $post_data['inning_id'];

        $batting_details = Batsmen::where('inning_id', $inning_id)->get();

        $bowling_details = Bowler::where('inning_id', $inning_id)->get();

        if ($batting_details->isEmpty()) {
            $batting_details = "No batting details found for this inning";
        }

        if ($bowling_details->isEmpty()) {
            $bowling_details = "No bowling details found for this inning";
        }

        $inning_details = [
            'inning_id' => $inning_id,
            'batting_details' => $batting_details,
            'bowling_details' => $bowling_details
        ];

        return $inning_details;
    }
}
