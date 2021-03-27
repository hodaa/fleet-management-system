<?php

namespace Tests\Unit\Services;

use App\Models\Bus;
use App\Models\LineOrder;
use App\Models\Station;
use App\Models\User;
use App\Services\TripsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Line;

class AvailableSeatsTest extends TestCase
{
    use RefreshDatabase;

    private $tripsService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tripsService = new TripsService();
        factory(User::class)->create(['email'=>'user@gmail.com']);

        factory(Station::class)->create(['name'=>'cairo']);
        factory(Station::class)->create(['name'=>'giza']);
        factory(Station::class)->create(['name'=>'alFayyum']);
        factory(Station::class)->create(['name'=>'alMinya']);
        factory(Station::class)->create(['name'=>'asyut']);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testOneSeatAvailable()
    {
        $line = factory(Line::class)->create(['start_station_id'=>1,'end_station_id'=>4]);

        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 1 ,'next_station'=>2, 'order'=>1]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 2 ,'next_station'=>3, 'order'=>2]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 3,'next_station'=>4,  'order'=>3]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 4, 'order'=>4]);

        factory(Bus::class)->create(['line_id' => $line->id]);
        $response= $this->tripsService->getAvailableSeats(1, 4);
        $this->assertCount(1, $response);
    }
}
