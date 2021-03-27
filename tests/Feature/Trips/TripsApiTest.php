<?php

namespace Tests\Feature;

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

        factory(Station::class)->create(['name'=>'tanta']);
        factory(Station::class)->create(['name'=>'cairo']);

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

    public function testApiSucceeded()
    {
        $line = factory(Line::class)->create();
        factory(LineOrder::class)->create(['line_id'=>$line->id,'station_id'=>1, 'next_station'=>2,'order'=>1]);

        $this->get('/api/v1/trips?start=tanta&end=cairo', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ])->assertStatus(200);
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
        $line = factory(Line::class)->create();

        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 1 ,'next_station'=>2, 'order'=>1]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 2 ,'next_station'=>3, 'order'=>2]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 3,'next_station'=>4,  'order'=>3]);
        factory(LineOrder::class)->create(['line_id'=> $line->id, 'station_id'=> 4, 'order'=> 4]);

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
        $this->assertDatabaseHas('booked_seats', ['pickup_id'=>1,'destination_id'=>2 ,'user_id'=>1]);
        $response->assertStatus(200)->assertJson([
            'message'=>'The given data was invalid.',
            'data'=>[
                 'pickup_station' => 'tanta',
                 'destination_station' => 'cairo',
                 'user'=> 'hoda'
              ],
            "message"=>"Your seat booked successfully"
        ]);
    }
}
