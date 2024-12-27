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

Route::get('all_matches', [CricketSeriesController::class, 'get_all_matches']);

Route::get('venues', [CricketSeriesController::class, 'get_venues']);

Route::get('teams', [CricketSeriesController::class, 'get_teams']);
