<?php

use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->insert([
            [ 'name' => 'mansoura'],
            [ 'name' => 'sammnoud'],
            [ 'name' => 'tanta'],
            [ 'name' => 'cairo'],

        ]);

        DB::table('lines')->insert([
            ['start_station_id'=>1 ,'end_station_id'=>4],
        ]);

    }
}
