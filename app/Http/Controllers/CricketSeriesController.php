<?php

// app/Http/Controllers/CricketSeriesController.php
namespace App\Http\Controllers;

use App\Services\CricketSeriesService;
use Illuminate\Http\Request;

class CricketSeriesController extends Controller
{
    protected $cricketSeriesService;

    public function __construct(CricketSeriesService $cricketSeriesService)
    {
        $this->cricketSeriesService = $cricketSeriesService;
    }

    public function get_all_series()
    {
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_all_series();

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }


    public function get_series_details(Request $request)
    {
        $post_data = $request->all();
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_series_details($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }

    public function get_matches(Request $request)
    {
        $post_data = $request->all();
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_matches($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }

    public function get_venues(Request $request)
    {
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_venues();

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }

    public function get_teams(Request $request)
    {
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_teams();

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }


    public function get_innings_details(Request $request)
    {
        $post_data = $request->all();

        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_innings_details($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }


    public function get_partnership_details()
    {
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_partnership_details();

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }

    public function get_player_of_match()
    {
        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_player_of_match();

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }
}
