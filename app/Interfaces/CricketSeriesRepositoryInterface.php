<?php
// app/Repositories/CricketSeriesRepositoryInterface.php
namespace App\Interfaces;

interface CricketSeriesRepositoryInterface
{
    public function get_all_series();

    public function get_series_data($post_data);

    public function get_all_match_data();

    public function get_venues();

    public function get_teams();
}
