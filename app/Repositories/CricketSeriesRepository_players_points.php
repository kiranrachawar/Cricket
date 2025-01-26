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

        $points = [
            'batting_points' => [],
            'bowling_points' => [],
        ];

        $points2 = [
            'bowling_points2' => [],
        ];

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
                        ->get()
                        ->groupBy('player_id');

                    $index = 1;
                    if ($batsmens->isNotEmpty()) {

                        foreach ($batsmens as $player_id => $player_data) {

                            $runs_point =  0;
                            $fours_point = 0;
                            $sixes_point =  0;
                            // $thirty_runs_bonus = 0;
                            $half_century_bonus = 0;
                            $century_bonus = 0;

                            $runs_point = $player_data->sum(function ($player) {
                                return $player->runs ?? 0;
                            });

                            $fours_point = $player_data->sum(function ($player) {
                                return $player->fours ?? 0;
                            });

                            $sixes_point = $player_data->sum(function ($player) {
                                return $player->sixes ? $player->sixes * 2 :  0;
                            });


                            foreach ($player_data as $data) {
                                // if ($data->runs > 29 && $data->runs < 50) {
                                //     $thirty_runs_bonus += 4;
                                // } else {
                                //     $thirty_runs_bonus += 0;
                                // }

                                if ($data->runs > 49 && $data->runs < 100) {
                                    $half_century_bonus += 4;
                                } else {
                                    $half_century_bonus += 0;
                                }

                                if ($data->runs > 99) {
                                    $century_bonus += 8;
                                } else {
                                    $century_bonus += 0;
                                }
                            }

                            $batting_points = [
                                //batting points
                                'Player_id' => $player_id ?? null,
                                'player_name' => $player_data->first()->name ?? null,
                                'runs_point' => $runs_point,
                                'fours_point' => $fours_point,
                                'sixes_point' => $sixes_point,
                                'half_century_bonus' => $half_century_bonus,
                                'century_bonus' => $century_bonus
                            ];

                            $points['batting_points'][] = $batting_points;
                        }
                    }



                    //BOWLING POINTS
                    $bowlers = Bowler::where('match_id', $match_id)
                        ->whereIn('inning_id', $innings)
                        ->get()
                        ->groupBy('player_id');

                    $get_batsmens_array = [];
                    $index = 1;
                    if ($bowlers->isNotEmpty()) {

                        foreach ($bowlers as $player_id => $bowler_data) {

                            $total_wickets = 0;
                            $wicket_points =  0;
                            $lbw_or_bowled_wicket_points = 0;
                            $four_wickets_bonus_points =  0;
                            $five_wickets_bonus_points =  0;

                            $wicket_points = $bowler_data->sum(function ($player) {
                                return $player->wickets ? $player->wickets * 16 : 0;
                            });

                            $total_wickets = $bowler_data->sum(function ($player) {
                                return $player->wickets ??  0;
                            });

                            if ($total_wickets === 4) {
                                $four_wickets_bonus_points += 4;
                            }

                            if ($total_wickets === 5) {
                                $five_wickets_bonus_points += 5;
                            }


                            $get_batsmensss = Batsmen::where('match_id', $match_id)
                                ->whereIn('inning_id', $innings)
                                ->where('bowler_id', $player_id)
                                ->get();

                            $get_batsmens = Batsmen::where('match_id', $match_id)
                                ->whereIn('inning_id', $innings)
                                ->where('bowler_id', $player_id)
                                ->get()
                                ->groupBy('bowler_id');




                            // echo "get_batsmens" . PHP_EOL;
                            // echo $get_batsmens;
                            // echo "get_batsmens" . PHP_EOL;




                            foreach ($get_batsmens as $bowler_id => $get_batsmens_data) {


                                $lbw_or_bowled_wicket_points = $get_batsmens_data->sum(function ($bowler) {
                                    return $bowler->wicket_code == 'BOWLED' || $bowler->wicket_code == 'LBW' ? 8 : 0;
                                });

                                $bowling_points2 = [
                                    //batting points
                                    'bowler_id' => $bowler_id ?? null,
                                    'player_name' => $bowler_data->first()->name ?? null,
                                    'lbw_or_bowled_wicket_points' => $lbw_or_bowled_wicket_points
                                ];

                                $points2['bowling_points2'][] = $bowling_points2;
                            }

                            // echo "get_batsmens";
                            // echo $get_batsmens;
                            // echo "get_batsmens";

                            array_push($get_batsmens_array, $bowling_points2);

                            // $get_batsmens_store = [
                            //     'data' =>
                            // ]


                            $bowling_points = [
                                //batting points
                                'Player_id' => $player_id ?? null,
                                'player_name' => $bowler_data->first()->name ?? null,
                                'total_wickets' => $total_wickets,
                                'wicket_points' => $wicket_points,
                                'lbw_or_bowled_wicket_points' => $lbw_or_bowled_wicket_points,
                                'four_wickets_bonus_points' => $four_wickets_bonus_points,
                                'five_wickets_bonus_points' => $five_wickets_bonus_points,
                            ];

                            $points['bowling_points'][] = $bowling_points;
                        }
                    }
                }
            }
        }

        return $points2;
    }




    //BELOW TWO FUNTIONS ARE FOR REFRENCE FOR T20 MATCHES 
    // public function get_player_points_details($post_data)
    // {
    //     $match_id = $post_data['matchId'];
    //     //$inning_id = $post_data['inningId'];
    //     $series_id = $post_data['seriesId'];

    //     if (!empty($match_id)) {

    //         $innings = InningsScore::where([
    //             'series_id' => $series_id,
    //             'match_id' => $match_id,
    //         ])->get()->pluck('inning_id');

    //         if (count($innings) > 0) {

    //             if (!empty($innings)) {

    //                 //BATTING POINTS
    //                 $batsmens = Batsmen::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->get()
    //                     ->groupBy('player_id');

    //                 $points = [];

    //                 foreach ($batsmens as $player_id => $playerEntries) {
    //                     $total_runs = 0;
    //                     $total_fours = 0;
    //                     $total_sixes = 0;
    //                     $strike_rate_points = 0;
    //                     $thirty_runs_bonus = 0;
    //                     $half_century_bonus = 0;
    //                     $century_bonus = 0;

    //                     foreach ($playerEntries as $batsman) {
    //                         // Add runs, fours, sixes
    //                         $total_runs += $batsman->runs ?? 0;
    //                         $total_fours += $batsman->fours ?? 0;
    //                         $total_sixes += ($batsman->sixes ?? 0) * 2;

    //                         // Calculate strike rate points for each entry
    //                         if (isset($batsman->strike_rate)) {
    //                             if ($batsman->strike_rate > 170) {
    //                                 $strike_rate_points += 6;
    //                             } elseif ($batsman->strike_rate > 150 && $batsman->strike_rate <= 170) {
    //                                 $strike_rate_points += 4;
    //                             } elseif ($batsman->strike_rate > 130 && $batsman->strike_rate <= 150) {
    //                                 $strike_rate_points += 2;
    //                             } elseif ($batsman->strike_rate > 60 && $batsman->strike_rate < 70) {
    //                                 $strike_rate_points -= 2;
    //                             } elseif ($batsman->strike_rate > 50 && $batsman->strike_rate <= 59.99) {
    //                                 $strike_rate_points -= 4;
    //                             } elseif ($batsman->strike_rate < 50) {
    //                                 $strike_rate_points -= 6;
    //                             }
    //                         }
    //                     }

    //                     // Bonus points based on total runs
    //                     if ($total_runs > 29 && $total_runs < 50) {
    //                         $thirty_runs_bonus = 4;
    //                     }

    //                     if ($total_runs > 49 && $total_runs < 100) {
    //                         $half_century_bonus = 8;
    //                     }

    //                     if ($total_runs > 99) {
    //                         $century_bonus = 16;
    //                     }

    //                     $player_points = [
    //                         'Player_id' => $player_id,
    //                         'player_name' => $playerEntries->first()->name ?? null,
    //                         'runs_point' => $total_runs,
    //                         'fours_point' => $total_fours,
    //                         'sixes_point' => $total_sixes,
    //                         'strike_rate_points' => $strike_rate_points,
    //                         'thirty_runs_bonus' => $thirty_runs_bonus,
    //                         'half_century_bonus' => $half_century_bonus,
    //                         'century_bonus' => $century_bonus
    //                     ];

    //                     $points[] = $player_points;
    //                 }
    //             }
    //         }
    //     }

    //     return $points;
    // }



    // public function get_player_points_details($post_data)
    // {
    //     $match_id = $post_data['matchId'];
    //     //$inning_id = $post_data['inningId'];
    //     $series_id = $post_data['seriesId'];

    //     $points = [];

    //     if (!empty($match_id)) {

    //         $innings = InningsScore::where([
    //             'series_id' => $series_id,
    //             'match_id' => $match_id,
    //         ])->get()->pluck('inning_id');

    //         if (count($innings) > 0) {

    //             if (!empty($innings)) {

    //                 //BATTING POINTS
    //                 $batsmens = Batsmen::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->get()
    //                     ->groupBy('player_id');

    //                 $index = 1;
    //                 if ($batsmens->isNotEmpty()) {

    //                     foreach ($batsmens as $player_id => $player_data) {


    //                         $runs_point =  0;
    //                         $fours_point = 0;
    //                         $sixes_point =  0;
    //                         $strike_rate_points = 0;

    //                         $runs_point = $player_data->sum(function ($player) {
    //                             return $player->runs ?? 0;
    //                         });

    //                         $fours_point = $player_data->sum(function ($player) {
    //                             return $player->fours ?? 0;
    //                         });

    //                         $sixes_point = $player_data->sum(function ($player) {
    //                             return $player->sixes ? $player->sixes * 2 :  0;
    //                         });




    //                         if (isset($player_data->strike_rate)) {
    //                             if ($player_data->strike_rate > 170) {
    //                                 $strike_rate_points = 6;
    //                             } elseif ($player_data->strike_rate > 150 && $player_data->strike_rate < 170) {
    //                                 $strike_rate_points = 4;
    //                             } elseif ($player_data->strike_rate > 130 && $player_data->strike_rate < 150) {
    //                                 $strike_rate_points = 2;
    //                             } elseif ($player_data->strike_rate > 60 && $player_data->strike_rate < 70) {
    //                                 $strike_rate_points = -2;
    //                             } elseif ($player_data->strike_rate > 50 && $player_data->strike_rate < 59.99) {
    //                                 $strike_rate_points = -4;
    //                             } elseif ($player_data->strike_rate < 50) {
    //                                 $strike_rate_points = -6;
    //                             }
    //                         }

    //                         // if ($player_data->runs > 29 && $player_data->runs < 50) {
    //                         //     $thirty_runs_bonus = 4;
    //                         // } else {
    //                         //     $thirty_runs_bonus = 0;
    //                         // }

    //                         // if ($player_data->runs > 49 && $player_data->runs < 100) {
    //                         //     $half_century_bonus = 8;
    //                         // } else {
    //                         //     $half_century_bonus = 0;
    //                         // }

    //                         // if ($player_data->runs > 99) {
    //                         //     $century_bonus = 16;
    //                         // } else {
    //                         //     $century_bonus = 0;
    //                         // }


    //                         $batting_points = [
    //                             //batting points
    //                             // 'id' => $player_data->id,
    //                             // 'Player_id' => $player_data->player_id ?? null,
    //                             // 'player_name' => $player_data->name ?? null,
    //                             'runs_point' => $runs_point,
    //                             'fours_point' => $fours_point,
    //                             'sixes_point' => $sixes_point,
    //                             'strike_rate_points' => $strike_rate_points,
    //                             // 'strike_rate_points' => $player_data->strike_rate

    //                             // 'thirty_runs_bonus' => $thirty_runs_bonus,
    //                             // 'half_century_bonus' => $half_century_bonus,
    //                             // 'century_bonus' => $century_bonus
    //                         ];

    //                         $points[] = $batting_points;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return $points;
    // }









    // public function get_player_points_details($post_data)
    // {
    //     $match_id = $post_data['matchId'];
    //     //$inning_id = $post_data['inningId'];
    //     $series_id = $post_data['seriesId'];

    //     $points = [
    //         'batting_points' => [],
    //         'bowling_points' => [],
    //         'fielding_points' => []
    //     ];

    //     $player_all_points = [];

    //     // $points2 = [
    //     //     'bowling_points2' => [],
    //     // ];

    //     if (!empty($match_id)) {

    //         $innings = InningsScore::where([
    //             'series_id' => $series_id,
    //             'match_id' => $match_id,
    //         ])->get()->pluck('inning_id');

    //         if (count($innings) > 0) {

    //             if (!empty($innings)) {

    //                 //Batting Points Start
    //                 $batsmens = Batsmen::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->get()
    //                     ->groupBy('player_id');

    //                 $index = 1;
    //                 if ($batsmens->isNotEmpty()) {

    //                     foreach ($batsmens as $player_id => $player_data) {

    //                         $runs_point =  0;
    //                         $fours_point = 0;
    //                         $sixes_point =  0;
    //                         // $thirty_runs_bonus = 0;
    //                         $half_century_bonus = 0;
    //                         $century_bonus = 0;

    //                         $runs_point = $player_data->sum(function ($player) {
    //                             return $player->runs ?? 0;
    //                         });

    //                         $fours_point = $player_data->sum(function ($player) {
    //                             return $player->fours ?? 0;
    //                         });

    //                         $sixes_point = $player_data->sum(function ($player) {
    //                             return $player->sixes ? $player->sixes * 2 :  0;
    //                         });


    //                         foreach ($player_data as $data) {
    //                             // if ($data->runs > 29 && $data->runs < 50) {
    //                             //     $thirty_runs_bonus += 4;
    //                             // } else {
    //                             //     $thirty_runs_bonus += 0;
    //                             // }

    //                             if ($data->runs > 49 && $data->runs < 100) {
    //                                 $half_century_bonus += 4;
    //                             } else {
    //                                 $half_century_bonus += 0;
    //                             }

    //                             if ($data->runs > 99) {
    //                                 $century_bonus += 8;
    //                             } else {
    //                                 $century_bonus += 0;
    //                             }
    //                         }

    //                         $batting_points = [
    //                             //batting points
    //                             'Player_id' => $player_id ?? null,
    //                             'player_name' => $player_data->first()->name ?? null,
    //                             'runs_point' => $runs_point,
    //                             'fours_point' => $fours_point,
    //                             'sixes_point' => $sixes_point,
    //                             'half_century_bonus' => $half_century_bonus,
    //                             'century_bonus' => $century_bonus
    //                         ];

    //                         $points['batting_points'][] = $batting_points;

    //                         $player_all_points[$player_id] = array_merge(
    //                             $player_all_points[$player_id] ?? [],
    //                             $batting_points
    //                         );
    //                     }
    //                 }
    //                 //Batting Points End

    //                 //Bowling Points Start
    //                 $bowlers = Bowler::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->get()
    //                     ->groupBy('player_id');

    //                 $get_batsmens_array = [];
    //                 $index = 1;
    //                 if ($bowlers->isNotEmpty()) {

    //                     foreach ($bowlers as $player_id => $bowler_data) {

    //                         $total_wickets = 0;
    //                         $wicket_points =  0;
    //                         $lbw_or_bowled_wicket_points = 0;
    //                         $four_wickets_bonus_points =  0;
    //                         $five_wickets_bonus_points =  0;

    //                         $wicket_points = $bowler_data->sum(function ($player) {
    //                             return $player->wickets ? $player->wickets * 16 : 0;
    //                         });

    //                         $total_wickets = $bowler_data->sum(function ($player) {
    //                             return $player->wickets ??  0;
    //                         });

    //                         if ($total_wickets === 4) {
    //                             $four_wickets_bonus_points += 4;
    //                         }

    //                         if ($total_wickets === 5) {
    //                             $five_wickets_bonus_points += 5;
    //                         }


    //                         $get_batsmensss = Batsmen::where('match_id', $match_id)
    //                             ->whereIn('inning_id', $innings)
    //                             ->where('bowler_id', $player_id)
    //                             ->get();

    //                         $get_batsmens = Batsmen::where('match_id', $match_id)
    //                             ->whereIn('inning_id', $innings)
    //                             ->where('bowler_id', $player_id)
    //                             ->get()
    //                             ->groupBy('bowler_id');

    //                         foreach ($get_batsmens as $bowler_id => $get_batsmens_data) {
    //                             $lbw_or_bowled_wicket_points = $get_batsmens_data->sum(function ($bowler) {
    //                                 return $bowler->wicket_code == 'BOWLED' || $bowler->wicket_code == 'LBW' ? 8 : 0;
    //                             });
    //                         }

    //                         //  array_push($get_batsmens_array, $bowling_points2);

    //                         $bowling_points = [
    //                             //batting points
    //                             'Player_id' => $player_id ?? null,
    //                             'player_name' => $bowler_data->first()->name ?? null,
    //                             'total_wickets' => $total_wickets,
    //                             'wicket_points' => $wicket_points,
    //                             'lbw_or_bowled_wicket_points' => $lbw_or_bowled_wicket_points,
    //                             'four_wickets_bonus_points' => $four_wickets_bonus_points,
    //                             'five_wickets_bonus_points' => $five_wickets_bonus_points,
    //                         ];

    //                         $points['bowling_points'][] = $bowling_points;

    //                         //$player_all_points[$player_id] checking played id exist in batsmen point
    //                         //if exist the it will add bowling points in that bastmen points 
    //                         $player_all_points[$player_id] = array_merge(
    //                             $player_all_points[$player_id] ?? [],
    //                             $bowling_points
    //                         );
    //                     }
    //                 }
    //                 //Bowling Points End

    //                 //Fielding Points Start

    //                 $fielding_data = Batsmen::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->get();
    //                 // ->groupBy('player_id');


    //                 $fielder_one_ids = Batsmen::where('match_id', $match_id)
    //                     ->whereIn('inning_id', $innings)
    //                     ->where('fielder_id_one', '<>', 0)
    //                     ->get()
    //                     ->pluck('fielder_id_one')
    //                     ->toArray();

    //                 $fielder_one_count = [];

    //                 // print_r($fielder_one_ids);


    //                 $fielding_points = [];
    //                 $fielding_stats = []; // To track catches, stumpings, and runouts

    //                 $test = [
    //                     'fielder_catch_points' => [],


    //                 ]; // To track catches, stumpings, and runouts

    //                 foreach ($fielding_data as $player) {
    //                     // $test =  $player;
    //                     // array_push($player, $test);
    //                     $store_fione =
    //                         $player_id = $player->player_id;
    //                     $fielder_id_one = $player->fielder_id_one;
    //                     $fielder_id_two = $player->fielder_id_two;
    //                     $fielder_id_three = $player->fielder_id_three;

    //                     $wicket_code = $player->wicket_code;

    //                     // // $test['fielder_id_two'][] = $fielder_id_two;
    //                     // // $test['fielder_id_three'][] = $fielder_id_three;



    //                     // // $fielder_catch_points = 0;

    //                     // if ($wicket_code === 'CAUGHT' && !empty($fielder_id_one)) {

    //                     //     $fielder_one_ids = Batsmen::where('match_id', $match_id)
    //                     //         ->whereIn('inning_id', $innings)
    //                     //         ->where('fielder_id_one', '<>', 0)
    //                     //         ->where('fielder_id_one', '=', $fielder_id_one)
    //                     //         ->get();
    //                     //     // ->groupBy($fielder_id_one);




    //                     //     if (isset($fielder_one_count[$fielder_id_one])) {
    //                     //         $fielder_one_count[$fielder_id_one]++;
    //                     //     } else {
    //                     //         $fielder_one_count[$fielder_id_one] = 1;
    //                     //     }

    //                     //     $fielder_catch_points = count($fielder_one_ids) * 8;
    //                     //     $test[$fielder_id_one]['fielder_catch_points'][] = $fielder_catch_points;
    //                     // }

    //                     // // print_r($fielder_catch_points) . '</br>';


    //                     // // $test['fielding_catch_data'][] = $fielding_catch_data;


    //                     // // $test['wicket_code'][] = $wicket_code;


    //                     // $fielding_points_data = [
    //                     //     'Player_id' => $player_id,
    //                     //     //ielding_points' => $points,
    //                     //     'catches' => $fielding_stats[$player_id] ?? 0,
    //                     //     'stumpings' => $fielding_stats[$player_id]['stumpings'] ?? 0,
    //                     //     'runouts' => $fielding_stats[$player_id]['runouts'] ?? 0,
    //                     // ];


    //                     // $points['fielding_points'][] = $fielding_points_data;


    //                     // // Merge the fielding points into the player's existing data
    //                     // $player_all_points[$player_id] = array_merge(
    //                     //     $player_all_points[$player_id] ?? [],
    //                     //     $fielding_points_data
    //                     // );




    //                     // Initialize stats for each fielder
    //                     if ($fielder_id_one) {
    //                         $fielding_stats[$fielder_id_one]['catches'] = ($fielding_stats[$fielder_id_one]['catches'] ?? 0);
    //                         $fielding_stats[$fielder_id_one]['stumpings'] = ($fielding_stats[$fielder_id_one]['stumpings'] ?? 0);
    //                         $fielding_stats[$fielder_id_one]['runouts'] = ($fielding_stats[$fielder_id_one]['runouts'] ?? 0);
    //                     }
    //                     if ($fielder_id_two) {
    //                         $fielding_stats[$fielder_id_two]['runouts'] = ($fielding_stats[$fielder_id_two]['runouts'] ?? 0);
    //                     }
    //                     if ($fielder_id_three) {
    //                         $fielding_stats[$fielder_id_three]['runouts'] = ($fielding_stats[$fielder_id_three]['runouts'] ?? 0);
    //                     }

    //                     // Catch points
    //                     if ($fielder_id_one && $wicket_code === 'CAUGHT') {
    //                         $fielding_points[$fielder_id_one] = ($fielding_points[$fielder_id_one] ?? 0) + 8;
    //                         $fielding_stats[$fielder_id_one]['catches'] += 1;
    //                         // $fielding_points[$fielder_id_one]['catches'] = $fielding_stats[$fielder_id_one]['catches'] ? $fielding_stats[$fielder_id_one]['catches'] * 8 : 0;
    //                     }
    //                     if ($fielder_id_two  && $wicket_code === 'CAUGHT') {
    //                         $fielding_points[$fielder_id_two] = ($fielding_points[$fielder_id_two] ?? 0) + 8;
    //                         $fielding_stats[$fielder_id_two]['catches'] += 1;
    //                     }
    //                     if ($fielder_id_three  && $wicket_code === 'CAUGHT') {
    //                         $fielding_points[$fielder_id_three] = ($fielding_points[$fielder_id_three] ?? 0) + 8;
    //                         $fielding_stats[$fielder_id_three]['catches'] += 1;
    //                     }

    //                     // Stumping points
    //                     if ($wicket_code === 'STUMPED') {
    //                         if ($fielder_id_one) {
    //                             $fielding_points[$fielder_id_one] = ($fielding_points[$fielder_id_one] ?? 0) + 12;
    //                             $fielding_stats[$fielder_id_one]['stumpings'] += 1;
    //                         }
    //                     }

    //                     // Run-out points
    //                     if ($wicket_code === 'RUN_OUT') {
    //                         if ($fielder_id_two || $fielder_id_three) {
    //                             // Assisted run-out
    //                             if ($fielder_id_two) {
    //                                 $fielding_points[$fielder_id_two] = ($fielding_points[$fielder_id_two] ?? 0) + 6;
    //                                 $fielding_stats[$fielder_id_two]['runouts'] += 1;
    //                             }
    //                             if ($fielder_id_three) {
    //                                 $fielding_points[$fielder_id_three] = ($fielding_points[$fielder_id_three] ?? 0) + 6;
    //                                 $fielding_stats[$fielder_id_three]['runouts'] += 1;
    //                             }
    //                         } else {
    //                             // Direct hit
    //                             if ($fielder_id_one) {
    //                                 $fielding_points[$fielder_id_one] = ($fielding_points[$fielder_id_one] ?? 0) + 12;
    //                                 $fielding_stats[$fielder_id_one]['runouts'] += 1;
    //                             }
    //                         }
    //                     }

    //                     $fielding_points_data = [
    //                         'Player_id' => $player_id,
    //                         'catches' => $fielding_stats[$player_id]['catches'] ?? 0,
    //                         'stumpings' => $fielding_stats[$player_id]['stumpings'] ?? 0,
    //                         'runouts' => $fielding_stats[$player_id]['runouts'] ?? 0,
    //                     ];

    //                     $points['fielding_points'][] = $fielding_points_data;


    //                     // Merge the fielding points into the player's existing data
    //                     $player_all_points[$player_id] = array_merge(
    //                         $player_all_points[$player_id] ?? [],
    //                         $fielding_points_data
    //                     );
    //                 }


    //                 //Fielding Points End


    //             }
    //         }
    //     }

    //     return $player_all_points;
    // }
}
