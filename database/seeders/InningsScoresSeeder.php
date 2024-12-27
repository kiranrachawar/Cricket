<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InningsScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Load the match details from the JSON file
        $data = json_decode(file_get_contents(storage_path('app/match_details.json')), true);

        // Store Innings Scores
        $innings_scores = [];
        foreach ($data['matchDetails'] as $matchDetails) {
            if (isset($matchDetails['matchDetailsMap']['match'])) {
                foreach ($matchDetails['matchDetailsMap']['match'] as $match) {
                    $matchId = $match['matchInfo']['matchId'];
                    $team1 = $match['matchInfo']['team1'];
                    $team2 = $match['matchInfo']['team2'];

                    // Team 1 Innings Scores
                    if (isset($match['matchScore']['team1Score'])) {
                        $innings_scores[] = [
                            'match_id' => $matchId,
                            'team_id' => $team1['teamId'],
                            'runs' => $match['matchScore']['team1Score']['inngs1']['runs'],
                            'wickets' => $match['matchScore']['team1Score']['inngs1']['wickets'] ?? 0,
                            'overs' => $match['matchScore']['team1Score']['inngs1']['overs'],
                            'inning_number' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];

                        // If there are more innings for team 1
                        if (isset($match['matchScore']['team1Score']['inngs2'])) {
                            $innings_scores[] = [
                                'match_id' => $matchId,
                                'team_id' => $team1['teamId'],
                                'runs' => $match['matchScore']['team1Score']['inngs2']['runs'],
                                'wickets' => $match['matchScore']['team1Score']['inngs2']['wickets'] ?? 0,
                                'overs' => $match['matchScore']['team1Score']['inngs2']['overs'],
                                'inning_number' => 2,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }

                    // Team 2 Innings Scores
                    if (isset($match['matchScore']['team2Score'])) {
                        $innings_scores[] = [
                            'match_id' => $matchId,
                            'team_id' => $team2['teamId'],
                            'runs' => $match['matchScore']['team2Score']['inngs1']['runs'],
                            'wickets' => $match['matchScore']['team2Score']['inngs1']['wickets'] ?? 0,
                            'overs' => $match['matchScore']['team2Score']['inngs1']['overs'],
                            'inning_number' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];

                        // If there are more innings for team 2
                        if (isset($match['matchScore']['team2Score']['inngs2'])) {
                            $innings_scores[] = [
                                'match_id' => $matchId,
                                'team_id' => $team2['teamId'],
                                'runs' => $match['matchScore']['team2Score']['inngs2']['runs'],
                                'wickets' => $match['matchScore']['team2Score']['inngs2']['wickets'] ?? 0,
                                'overs' => $match['matchScore']['team2Score']['inngs2']['overs'],
                                'inning_number' => 2,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }
            }
        }

        // Insert the innings scores into the database
        DB::table('innings_scores')->upsert($innings_scores, ['match_id', 'team_id', 'inning_number'], ['runs', 'wickets', 'overs', 'updated_at']);
    }
}
