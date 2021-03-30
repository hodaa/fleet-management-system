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
            ['start_station_id'=>4 ,'end_station_id'=>2],
        ]);

        DB::table('line_orders')->insert([
            [
                'line_id' => 1 ,
                'station_id' => 1,
                'next_station'=> 2,
                'station_order' => 1

            ],
            [
                'line_id' => 1 ,
                'station_id' => 2,
                'next_station'=> 3,
                'station_order' => 2

            ],
            [
                'line_id' => 1 ,
                'station_id' => 3,
                'next_station'=> 4,
                'station_order' => 3

            ],
            [
                'line_id' => 1 ,
                'station_id' => 4,
                'station_order' => 4,
                'next_station'=> null,

            ],
            [
                'line_id' => 2 ,
                'station_id' => 4,
                'next_station'=> 3,
                'station_order' => 1

            ],
            [
                'line_id' => 2 ,
                'station_id' => 3,
                'next_station'=> 2,
                'station_order' => 2

            ],
            [
                'line_id' => 2 ,
                'station_id' => 2,
                'next_station'=> 1,
                'station_order' => 3

            ],
            [
                'line_id' => 2 ,
                'station_id' => 1,
                'station_order' => 4,
                'next_station'=> null,

            ],
        ]);

        DB::table('buses')->insert([
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ1',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ2',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ3',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ4',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ5',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ6',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ7',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ8',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ9',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ10',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ11',
                'line_id'=> 1,

            ],
            [
                'bus_no' => 1 ,
                'seat_no' => 'XYZ12',
                'line_id'=> 1,

            ],

        ]);
    }
}
