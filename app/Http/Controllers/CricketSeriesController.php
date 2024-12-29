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

        // Check if 'series_id' is not set
        if (!isset($post_data['series_id'])) {
            return response()->json(['status' => 404, 'message' => 'Series ID is not set']);
        }

        // Check if 'series_id' is empty
        if (empty($post_data['series_id'])) {
            return response()->json(['status' => 404, 'message' => 'Series ID should not be empty']);
        }

        // Call the service method to get the data
        $data = $this->cricketSeriesService->get_series_details($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }

    public function get_matches(Request $request)
    {
        $post_data = $request->all();

        // Check if 'series_id' is not set
        if (!isset($post_data['series_id'])) {
            return response()->json(['status' => 404, 'message' => 'Series ID is not set']);
        }

        // Check if 'series_id' is empty
        if (empty($post_data['series_id'])) {
            return response()->json(['status' => 404, 'message' => 'Series ID should not be empty']);
        }

        $data = $this->cricketSeriesService->get_matches($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
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
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
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
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }


    public function get_innings(Request $request)
    {
        $post_data = $request->all();

        // Check if 'match_id' is not set
        if (!isset($post_data['match_id'])) {
            return response()->json(['status' => 404, 'message' => 'Match ID is not set']);
        }

        // Check if 'match_id' is empty
        if (empty($post_data['match_id'])) {
            return response()->json(['status' => 404, 'message' => 'Match ID should not be empty']);
        }

        $data = $this->cricketSeriesService->get_innings($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }


    public function get_partnership_details(Request $request)
    {
        $post_data = $request->all();

        // Check if 'match_id' is not set
        if (!isset($post_data['match_id']) || !isset($post_data['inning_id'])) {
            return response()->json(['status' => 404, 'message' => 'Post data is not set']);
        }

        // Check if 'match_id' is empty
        if (empty($post_data['match_id']) || empty($post_data['inning_id'])) {
            return response()->json(['status' => 404, 'message' => 'Post data should not be empty']);
        }

        $data = $this->cricketSeriesService->get_partnership_details($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
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

    public function get_inning_details(Request $request)
    {
        $post_data = $request->all();

        // Check if 'inning_id' is not set
        if (!isset($post_data['inning_id'])) {
            return response()->json(['status' => 404, 'message' => 'Inning ID is not set']);
        }

        // Check if 'inning_id' is empty
        if (empty($post_data['inning_id'])) {
            return response()->json(['status' => 404, 'message' => 'Inning ID should not be empty']);
        }

        $data = $this->cricketSeriesService->get_inning_details($post_data);

        // Return the data as JSON
        if ($data['status'] == 200) {
            return response()->json(['status' => 200, 'data' => $data]);
        } elseif ($data['status'] == 300) {
            return response()->json(['status' => 300, 'message' => $data['message'], 'data' => $data['data']]);
        } else {
            return response()->json(['status' => 400, 'error' => 'Something went wrong']);
        }
    }
}
