<?php

// app/Services/CricketSeriesService.php
namespace App\Services;

use App\Repositories\CricketSeriesRepository;

class CricketSeriesService
{
    protected $cricketSeriesRepository;

    public function __construct(CricketSeriesRepository $cricketSeriesRepository)
    {
        $this->cricketSeriesRepository = $cricketSeriesRepository;
    }


    public function get_all_series()
    {
        $response = [];

        // Additional business logic can be added here
        $get_all_series =  $this->cricketSeriesRepository->get_all_series();

        if (count($get_all_series) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Data Found Successfully',
                'data' => $get_all_series
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Data Not Found',
                'data' => $get_all_series
            ];
        }

        return $response;
    }


    public function get_series_details($post_data)
    {
        $response = [];

        // Additional business logic can be added here
        $get_series_data =  $this->cricketSeriesRepository->get_series_data($post_data);

        if (count($get_series_data) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Data Found Successfully',
                'data' => $get_series_data
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Data Not Found',
                'data' => $get_series_data
            ];
        }

        return $response;
    }


    public function get_matches($post_data)
    {
        $response = [];

        // Additional business logic can be added here
        $get_matches =  $this->cricketSeriesRepository->get_matche_data($post_data);

        if (count($get_matches) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Match Found Successfully',
                'data' => $get_matches
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Data Not Found',
                'data' => $get_matches
            ];
        }

        return $response;
    }

    public function get_venues()
    {
        $response = [];

        // Additional business logic can be added here
        $get_venues =  $this->cricketSeriesRepository->get_venues();

        if (count($get_venues) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Venues Found Successfully',
                'data' => $get_venues
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Venues Not Found',
                'data' => $get_venues
            ];
        }

        return $response;
    }


    public function get_teams()
    {
        $response = [];

        // Additional business logic can be added here
        $get_teams =  $this->cricketSeriesRepository->get_teams();

        if (count($get_teams) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Teams Found Successfully',
                'data' => $get_teams
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Teams Not Found',
                'data' => $get_teams
            ];
        }

        return $response;
    }


    public function get_innings($post_data)
    {
        $response = [];

        // Additional business logic can be added here
        $innings =  $this->cricketSeriesRepository->get_innings($post_data);

        if (count($innings) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Innings Found Successfully',
                'data' => $innings
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Innings Not Found',
                'data' => $innings
            ];
        }

        return $response;
    }


    public function get_partnership_details($post_data)
    {
        $response = [];

        // Additional business logic can be added here
        $partnership_details =  $this->cricketSeriesRepository->partnership_details($post_data);

        if (count($partnership_details) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Partnership Details Found Successfully',
                'data' => $partnership_details
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Partnership Details Not Found',
                'data' => $partnership_details
            ];
        }

        return $response;
    }

    public function get_player_of_match()
    {
        $response = [];

        // Additional business logic can be added here
        $player_of_match =  $this->cricketSeriesRepository->player_of_match();

        if ($player_of_match) {
            $response = [
                'status' => 200,
                'message' => 'player of match Found Successfully',
                'data' => $player_of_match
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'player of match Not Found',
                'data' => $player_of_match
            ];
        }

        return $response;
    }


    public function get_inning_details($post_data)
    {
        $response = [];

        $innings_details =  $this->cricketSeriesRepository->get_inning_details($post_data);

        if (count($innings_details) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Innings Details Found Successfully',
                'data' => $innings_details
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Innings Details Not Found',
                'data' => $innings_details
            ];
        }

        return $response;
    }
}
