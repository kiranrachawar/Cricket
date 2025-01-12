<?php

use App\Http\Controllers\CricketSeriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('all_series', [CricketSeriesController::class, 'get_all_series']);

Route::get('series_details', [CricketSeriesController::class, 'get_series_details']);

Route::get('matches', [CricketSeriesController::class, 'get_matches']);

Route::get('venues', [CricketSeriesController::class, 'get_venues']);

Route::get('teams', [CricketSeriesController::class, 'get_teams']);

Route::get('innings', [CricketSeriesController::class, 'get_innings']);

Route::get('partnership', [CricketSeriesController::class, 'get_partnership_details']);

Route::get('player_of_match', [CricketSeriesController::class, 'get_player_of_match']);

Route::get('inning_details', [CricketSeriesController::class, 'get_inning_details']);

Route::get('player_points', [CricketSeriesController::class, 'get_player_points']);
