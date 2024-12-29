<?php
// app/Repositories/CricketSeriesRepositoryInterface.php
namespace App\Interfaces;

interface CricketSeriesRepositoryInterface
{
    public function get_all_series();

    public function get_series_data($post_data);

    public function get_matche_data($post_data);

    public function get_venues();

    public function get_teams();

    public function innings_details($post_data);

    public function partnership_details();

    public function player_of_match();
}
