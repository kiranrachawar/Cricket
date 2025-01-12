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
        $series_id = $post_data['seriesId'];

        $data = CricketSeries::where('series_id', $series_id)->get();

        return $data;
    }


    public function get_matche_data($post_data)
    {
        $series_id = $post_data['seriesId'];
        //$series_id = 7745;
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
        $series_id = $post_data['seriesId'];
        $match_id = $post_data['matchId'];
        // $innings = InningsScore::where('match_id', $match_id)->get();
        $innings = InningsScore::select(
            'innings_scores.*',
            'teams.name as team_name'
        )
            ->join('teams', 'innings_scores.team_id', '=', 'teams.id')
            ->where('innings_scores.series_id',  $series_id)
            ->where('innings_scores.match_id',  $match_id)
            ->get();

        return $innings;
    }

    public function partnership_details($post_data)
    {
        $match_id = $post_data['matchId'];
        $inning_id = $post_data['inningId'];

        $partnership = Partnership::where([
            ['match_id', '=', $match_id],
            ['inning_id', '=', $inning_id]
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
        $inning_id = $post_data['inningId'];
        $match_id = $post_data['matchId'];

        $batting_details = Batsmen::where([
            ['match_id', '=', $match_id],
            ['inning_id', '=', $inning_id]
        ])->get();

        $bowling_details = Bowler::where([
            ['match_id', '=', $match_id],
            ['inning_id', '=', $inning_id]
        ])->get();

        $partnership_details = Partnership::where([
            ['match_id', '=', $match_id],
            ['inning_id', '=', $inning_id]
        ])->get();


        if ($batting_details->isEmpty()) {
            $batting_details = "No batting details found for this inning";
        }

        if ($bowling_details->isEmpty()) {
            $bowling_details = "No bowling details found for this inning";
        }

        if ($partnership_details->isEmpty()) {
            $partnership_details = "No partnership details found for this inning";
        }

        $inning_details = [
            'inning_id' => $inning_id,
            'batting_details' => $batting_details,
            'bowling_details' => $bowling_details,
            'partnership_details' => $partnership_details
        ];

        return $inning_details;
    }




    public function get_player_points_details($post_data)
    {
        $match_id = $post_data['matchId'];
        //$inning_id = $post_data['inningId'];
        $series_id = $post_data['seriesId'];

        $points = [];

        if (!empty($match_id)) {

            $innings = InningsScore::where([
                'series_id' => $series_id,
                'match_id' => $match_id,
            ])->get()->pluck('inning_id');

            if (count($innings) > 0) {

                if (!empty($innings)) {

                    //BATTING POINTS
                    $batsmens = Batsmen::where('match_id', $match_id)
                        ->whereIn('inning_id', $innings)
                        ->get();

                    $index = 1;
                    if ($batsmens->isNotEmpty()) {

                        foreach ($batsmens as $batsmen) {
                            $runs_point = $batsmen->runs ?? 0;
                            $fours_point = $batsmen->fours ??  0;
                            $sixes_point = $batsmen->sixes ? $batsmen->sixes * 2 :  0;
                            $strike_rate_points = 0;

                            if (isset($batsmen->strike_rate)) {
                                if ($batsmen->strike_rate > 170) {
                                    $strike_rate_points = 6;
                                } elseif ($batsmen->strike_rate > 150 && $batsmen->strike_rate < 170) {
                                    $strike_rate_points = 4;
                                } elseif ($batsmen->strike_rate > 130 && $batsmen->strike_rate < 150) {
                                    $strike_rate_points = 2;
                                } elseif ($batsmen->strike_rate > 60 && $batsmen->strike_rate < 70) {
                                    $strike_rate_points = -2;
                                } elseif ($batsmen->strike_rate > 50 && $batsmen->strike_rate < 59.99) {
                                    $strike_rate_points = -4;
                                } elseif ($batsmen->strike_rate < 50) {
                                    $strike_rate_points = -6;
                                }
                            }

                            if ($batsmen->runs > 29 && $batsmen->runs < 50) {
                                $thirty_runs_bonus = 4;
                            } else {
                                $thirty_runs_bonus = 0;
                            }

                            if ($batsmen->runs > 49 && $batsmen->runs < 100) {
                                $half_century_bonus = 8;
                            } else {
                                $half_century_bonus = 0;
                            }

                            if ($batsmen->runs > 99) {
                                $century_bonus = 16;
                            } else {
                                $century_bonus = 0;
                            }


                            $batting_points = [
                                //batting points
                                'Player_id' => $batsmen->player_id ?? null,
                                'player_name' => $batsmen->name ?? null,
                                'runs_point' => $runs_point,
                                'fours_point' => $fours_point,
                                'sixes_point' => $sixes_point,
                                'strike_rate_points' => $strike_rate_points,
                                'thirty_runs_bonus' => $thirty_runs_bonus,
                                'half_century_bonus' => $half_century_bonus,
                                'century_bonus' => $century_bonus
                            ];

                            $points[] = $batting_points;
                        }
                    }


                    //BOWLING POINTS
                    $bowlers = Bowler::where('match_id', $match_id)
                        ->whereIn('inning_id', $innings)
                        ->get();

                    // echo "nowlers";
                    // print_r($bowlers);
                    // echo "nowlers";
                }
            }
        }

        return $points;
    }
}
