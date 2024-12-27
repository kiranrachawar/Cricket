<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = json_decode(file_get_contents(storage_path('app/match_details.json')), true);

        // Store Teams
        $teams = [];
        foreach ($data['matchDetails'] as $matchDetails) {
            if (isset($matchDetails['matchDetailsMap']['match'])) {
                foreach ($matchDetails['matchDetailsMap']['match'] as $match) {
                    $team1 = $match['matchInfo']['team1'];
                    $team2 = $match['matchInfo']['team2'];

                    $teams[$team1['teamId']] = [
                        'id' => $team1['teamId'],
                        'name' => $team1['teamName'],
                        'short_name' => $team1['teamSName'],
                        'image_id' => $team1['imageId']
                    ];

                    $teams[$team2['teamId']] = [
                        'id' => $team2['teamId'],
                        'name' => $team2['teamName'],
                        'short_name' => $team2['teamSName'],
                        'image_id' => $team2['imageId']
                    ];
                }
            }
        }
        DB::table('teams')->upsert($teams, ['id'], ['name', 'short_name', 'image_id']);

        // Store Venues
        $venues = [];
        foreach ($data['matchDetails'] as $matchDetails) {
            if (isset($matchDetails['matchDetailsMap']['match'])) {
                foreach ($matchDetails['matchDetailsMap']['match'] as $match) {
                    $venue = $match['matchInfo']['venueInfo'];

                    $venues[$venue['id']] = [
                        'id' => $venue['id'],
                        'ground' => $venue['ground'],
                        'city' => $venue['city'],
                        'timezone' => $venue['timezone']
                    ];
                }
            }
        }
        DB::table('venues')->upsert($venues, ['id'], ['ground', 'city', 'timezone']);


        // Store Match Details
        $matches = [];
        foreach ($data['matchDetails'] as $matchDetails) {
            if (isset($matchDetails['matchDetailsMap']['match'])) {
                foreach ($matchDetails['matchDetailsMap']['match'] as $match) {
                    $matchInfo = $match['matchInfo'];

                    $matches[] = [
                        'id' => $matchInfo['matchId'],
                        'series_id' => $matchInfo['seriesId'],
                        'description' => $matchInfo['matchDesc'],
                        'format' => $matchInfo['matchFormat'],
                        'start_date' => date('Y-m-d H:i:s', $matchInfo['startDate'] / 1000),
                        'end_date' => date('Y-m-d H:i:s', $matchInfo['endDate'] / 1000),
                        'status' => $matchInfo['status'],
                        'team1_id' => $matchInfo['team1']['teamId'],
                        'team2_id' => $matchInfo['team2']['teamId'],
                        'venue_id' => $matchInfo['venueInfo']['id']
                    ];
                }
            }
        }
        DB::table('matches')->upsert($matches, ['id'], ['series_id', 'description', 'format', 'start_date', 'end_date', 'status', 'team1_id', 'team2_id', 'venue_id']);
    }
}
