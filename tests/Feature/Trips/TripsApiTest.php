<?php

namespace Tests\Feature;

use App\Models\BookedSeat;
use App\Models\Bus;
use App\Models\Line;
use App\Models\LineOrder;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class TripsApiTests extends TestCase
{
    use RefreshDatabase;
    private $token;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');


        factory(Station::class)->create(['name'=>'mansoura']);
        factory(Station::class)->create(['name'=>'sammnoud']);
        factory(Station::class)->create(['name'=>'mahala']);
        factory(Station::class)->create(['name'=>'tanta']);
        factory(Station::class)->create(['name'=>'banha']);
        factory(Station::class)->create(['name'=>'cairo']);
        $line = factory(Line::class)->create();

        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>1, 'next_station'=>2]);
        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>2, 'next_station'=>3]);
        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>3, 'next_station'=>4]);
        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>4, 'next_station'=>5]);
        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>5, 'next_station'=>6]);

        $user= factory(User::class)->create([
            'email'=>'user@gmail.com',
            'password'=>Hash::make('12345678'),
            'name' => 'hoda'
        ]);

        $this->token = $user->createToken('TestToken')->accessToken;
    }

    public function testApiLogin()
    {
        $body = [
            'email' => 'user@gmail.com',
            'password' => '12345678'
        ];

        $this->json('POST', '/api/v1/login', $body, ['Accept' => 'application/json'])
           ->assertStatus(200)->assertJsonStructure(['token']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testListAvailableWithInValidFilters()
    {

        $response = $this->get('/api/v1/trips?start=aaaa&end=bbbb', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ]);
        $response->assertStatus(422)->assertExactJson([
            'message'=>'The given data was invalid.',
            'errors'=>[
                'start'=> ['The selected start is invalid.'],
                'end'=> ['The selected end is invalid.']
            ]
        ]);
    }

    public function testRouteNotExists()
    {

        $response = $this->get('/api/v1/trips?start=cairo&end=tanta', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ]);
        $response->assertStatus(422)->assertExactJson([
            'message'=>'The given data was invalid.',
            'errors'=>[
                'end'=> ['This Route Does not exist.']
            ]
        ]);
    }

    public function testAvailableSeatsApiSucceeded()
    {

        $res= $this->get('/api/v1/trips?start=tanta&end=cairo', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ])->assertStatus(200);
    }

    public function testAvailableSeatsForInBetweenStation()
    {
        factory(Bus::class)->create(['seat_no'=>'XYZ1']);
        factory(Bus::class)->create(['seat_no'=>'XYZ2']);
        factory(Bus::class)->create(['seat_no'=>'XYZ3']);

        $response= $this->get('/api/v1/trips?start=sammnoud&end=banha', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ]);
        $this->assertCount(3, $response->json()['data']);
    }

    public function testAvailableSeatsAfterBookingOne()
    {
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ1']);
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ2']);
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ3']);

        factory(BookedSeat::class)->create(['pickup_id'=>1 ,'destination_id'=>3]);

        $response= $this->get('/api/v1/trips?start=sammnoud&end=banha', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ]);
        $response->assertStatus(200)->assertJson([
            'data'=>[
                [
                    "bus_no"=>"1",
                    "seat_no"=>"XYZ1",
                    "pickup_station"=>"mahala",
                    "destination_station"=>"banha"
                ],
                [
                    "bus_no"=>"1",
                    "seat_no"=>"XYZ2",
                    "pickup_station"=>"sammnoud",
                    "destination_station"=>"banha"
                ],
                [
                    "bus_no"=>"1",
                    "seat_no"=>"XYZ3",
                    "pickup_station"=>"sammnoud",
                    "destination_station"=>"banha"
                ]

            ],
        ]);
    }

    public function testOneSeatIsFullyBooked()
    {
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ1']);
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ2']);
        factory(Bus::class)->create(['bus_no'=>1,'seat_no'=>'XYZ3']);

        factory(BookedSeat::class)->create(['bus_id'=>1,'pickup_id'=>1 ,'destination_id'=>6]);

        $response= $this->get('/api/v1/trips?start=sammnoud&end=cairo', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ]);

        $response->assertStatus(200)->assertJson([
            'data'=>[
                [
                    "bus_no"=>"1",
                    "seat_no"=>"XYZ2",
                    "pickup_station"=>"sammnoud",
                    "destination_station"=>"banha"
                ],
                [
                    "bus_no"=>"1",
                    "seat_no"=>"XYZ3",
                    "pickup_station"=>"sammnoud",
                    "destination_station"=>"banha"
                ]

            ],
        ]);
    }


    public function testBookingApiValidation()
    {
        $response = $this->post(
            '/api/v1/trip/book',
            [
                'pickup_point'=> 'aaaa',
                'destination_point'=>'bbb',
            ],
            [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

            ]
        );
        $response->assertStatus(422)->assertExactJson([
            'message'=>'The given data was invalid.',
            'errors'=>[
                "seat_id" => ["The seat id field is required."],
                "destination_point"=>["The selected destination point is invalid."],
                'pickup_point'=> ['The selected pickup point is invalid.'],

            ]
        ]);
    }

    public function testBookingApiSucceeded()
    {

        factory(Bus::class)->create(['seat_no'=>'XYZ1']);
        $response = $this->post(
            '/api/v1/trip/book',
            [
                'pickup_point'=> 'tanta',
                'destination_point'=>'cairo',
                'seat_id'=>'XYZ1'
            ],
            [
                'Accept'=>'application/json',
                'Authorization'=> 'Bearer '.$this->token
            ]
        );
        $this->assertDatabaseHas('booked_seats', [
            'bus_id'=>1,
            'pickup_id'=>4,
            'destination_id'=>6 ,
            'user_id'=>1
        ]);
        $response->assertStatus(200)->assertJson([
            'data'=>[
                 'pickup_station' => 'tanta',
                 'destination_station' => 'cairo',
                 'user'=> 'hoda'
              ],
            "message"=>"Your seat booked successfully"
        ]);
    }
}
