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

    public function get_innings($post_data);

    public function partnership_details($post_data);

    public function player_of_match();

    public function get_inning_details($post_data);
}
