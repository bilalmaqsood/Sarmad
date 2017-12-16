<?php

use Illuminate\Database\Seeder;
use database\SubscriptionsTableSeeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('SubscriptionsTableSeeder');
        $this->call('CountriesTableSeeder');
    }
}