<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScorecardOtherDeatilsSeeder extends Seeder
{
    public function run()
    {
        // Load the match details from the JSON file
        $data = json_decode(file_get_contents(storage_path('app/scorecard_details.json')), true);

        $matchTosses = [];  // New array for match tosses
        $playersOfTheMatch = [];  // New array for players of the match

        foreach ($data['scoreCard'] as $match) {
            $matchId = $match['matchId'];


            // Create match toss data
            $matchTosses[] = [
                'match_id' => $matchId,
                'toss_winner_name' => $data['matchHeader']['tossResults']['tossWinnerName'],
                'decision' => $data['matchHeader']['tossResults']['decision'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Create players of the match data
            foreach ($data['matchHeader']['playersOfTheMatch'] as $player) {
                $playersOfTheMatch[] = [
                    'match_id' => $matchId,
                    'player_id' => $player['id'],
                    'player_name' => $player['name'],
                    'team_name' => $player['teamName'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }


        // Insert match tosses
        DB::table('match_tosses')->upsert($matchTosses, ['match_id'], ['toss_winner_name', 'decision', 'updated_at']);

        // Insert players of the match
        DB::table('players_of_the_match')->upsert($playersOfTheMatch, ['match_id', 'player_id'], ['player_name', 'team_name', 'updated_at']);
    }
}
