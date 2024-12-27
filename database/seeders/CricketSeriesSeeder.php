<?php

namespace Database\Seeders;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CricketSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(storage_path('app/series_data.json')), true);

        foreach ($data['seriesMapProto'] as $group) {
            $groupDate = $group['date']; // e.g., "FEBRUARY 2024"
        
            foreach ($group['series'] as $series) {
                DB::table('cricket_series')->insert([
                    'series_id' => $series['id'], // Series ID
                    'series_date' => $groupDate, // Group date (e.g., "FEBRUARY 2024")
                    'name' => $series['name'], // Series name
                    'start_date' => (new DateTime('@' . ($series['startDt'] / 1000), new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata')) // Convert to GMT+05:30
                    ->format('Y-m-d H:i:s'),
                    'end_date' => (new DateTime('@' . ($series['endDt'] / 1000), new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata')) // Convert to GMT+05:30
                    ->format('Y-m-d H:i:s'),
                    'is_fantasy_handbook_enabled' => $series['isFantasyHandbookEnabled'] ?? null, // Optional boolean
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        
    }
}
