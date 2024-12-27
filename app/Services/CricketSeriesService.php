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


    public function get_all_matches($post_data)
    {
        $response = [];

        // Additional business logic can be added here
        $get_all_matches =  $this->cricketSeriesRepository->get_all_match_data();

        if (count($get_all_matches) > 0) {
            $response = [
                'status' => 200,
                'message' => 'Match Found Successfully',
                'data' => $get_all_matches
            ];
        } else {
            $response = [
                'status' => 300,
                'message' => 'Data Not Found',
                'data' => $get_all_matches
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
}
