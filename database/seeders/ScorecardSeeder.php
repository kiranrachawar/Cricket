<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScorecardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Load the match details from the JSON file
        $data = json_decode(file_get_contents(storage_path('app/scorecard_details.json')), true);

        // Store scorecard data
        $scorecards = [];
        $batsmen = [];
        $bowlers = [];
        $partnerships = [];
        $matchEvents = [];  // New array for match events
        $matchDetails = [];

        foreach ($data['scoreCard'] as $match) {
            $matchId = $match['matchId'];
            $inningsId = $match['inningsId'];
            $batTeamId = $match['batTeamDetails']['batTeamId'];
            $bowlTeamId = $match['bowlTeamDetails']['bowlTeamId'];

            // Create match header data
            $matchDetails[] = [
                'match_id' => $matchId,
                'match_description' => $data['matchHeader']['matchDescription'],
                'match_format' => $data['matchHeader']['matchFormat'],
                'match_type' => $data['matchHeader']['matchType'],
                'match_start_timestamp' => Carbon::createFromTimestampMs($data['matchHeader']['matchStartTimestamp']),
                'match_complete_timestamp' => Carbon::createFromTimestampMs($data['matchHeader']['matchCompleteTimestamp']),
                'day_night' => $data['matchHeader']['dayNight'],
                'state' => $data['matchHeader']['status'],
                'year' => $data['matchHeader']['year'],
                'status' => $data['matchHeader']['status'],
                'toss_winner' => $data['matchHeader']['tossResults']['tossWinnerName'],
                'toss_decision' => $data['matchHeader']['tossResults']['decision'],
                'winning_team' => $data['matchHeader']['result']['winningTeam'],
                'winning_margin' => $data['matchHeader']['result']['winningMargin'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Create scorecard data
            $scorecards[] = [
                'match_id' => $matchId,
                'bat_team_id' => $batTeamId,
                'bowl_team_id' => $bowlTeamId,
                'overs' => $match['scoreDetails']['overs'],
                'runs' => $match['scoreDetails']['runs'],
                'wickets' => $match['scoreDetails']['wickets'],
                'run_rate' => $match['scoreDetails']['runRate'],
                'extras' => $match['extrasData']['total'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Create batsmen data
            foreach ($match['batTeamDetails']['batsmenData'] as $batsman) {
                $batsmen[] = [
                    'inning_id' => $inningsId,
                    'match_id' => $matchId,
                    'name' => $batsman['batName'],
                    'is_captain' => $batsman['isCaptain'],
                    'is_keeper' => $batsman['isKeeper'],
                    'runs' => $batsman['runs'],
                    'balls' => $batsman['balls'],
                    'fours' => $batsman['fours'],
                    'sixes' => $batsman['sixes'],
                    'strike_rate' => $batsman['strikeRate'],
                    'out_desc' => $batsman['outDesc'] ?? null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Create bowlers data
            foreach ($match['bowlTeamDetails']['bowlersData'] as $bowler) {
                $bowlers[] = [
                    'inning_id' => $inningsId,
                    'match_id' => $matchId,
                    'name' => $bowler['bowlName'],
                    'overs' => $bowler['overs'],
                    'maidens' => $bowler['maidens'],
                    'runs' => $bowler['runs'],
                    'wickets' => $bowler['wickets'],
                    'economy' => $bowler['economy'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Create wickets data
            foreach ($match['wicketsData'] as $wicket) {
                $matchEvents[] = [
                    'match_id' => $matchId,
                    'wicket_batsman' => $wicket['batName'],
                    'wicket_number' => $wicket['wktNbr'],
                    'wicket_over' => $wicket['wktOver'],
                    'wicket_runs' => $wicket['wktRuns'],
                    'ball_number' => $wicket['ballNbr'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Create partnerships data
            foreach ($match['partnershipsData'] as $partnership) {
                $partnerships[] = [
                    'match_id' => $matchId,
                    'bat1_name' => $partnership['bat1Name'],
                    'bat2_name' => $partnership['bat2Name'],
                    'total_runs' => $partnership['totalRuns'],
                    'total_balls' => $partnership['totalBalls'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Insert match headers
        DB::table('match_details')->upsert($matchDetails, ['match_id'], ['match_description', 'match_format', 'status', 'updated_at']);

        // Insert scorecards, batsmen, bowlers, partnerships, and match events data
        DB::table('scorecards')->upsert($scorecards, ['match_id', 'bat_team_id', 'bowl_team_id'], ['overs', 'runs', 'wickets', 'run_rate', 'extras', 'updated_at']);
        DB::table('batsmen')->upsert($batsmen, ['match_id', 'name'], ['runs', 'balls', 'fours', 'sixes', 'strike_rate', 'out_desc', 'updated_at']);
        DB::table('bowlers')->upsert($bowlers, ['match_id', 'name'], ['overs', 'maidens', 'runs', 'wickets', 'economy', 'updated_at']);
        DB::table('partnerships')->upsert($partnerships, ['match_id', 'bat1_name', 'bat2_name'], ['total_runs', 'total_balls', 'updated_at']);
        DB::table('match_events')->upsert($matchEvents, ['match_id', 'wicket_batsman'], ['wicket_number', 'wicket_over', 'wicket_runs', 'ball_number', 'updated_at']);
    }
}
