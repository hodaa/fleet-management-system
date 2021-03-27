<?php

namespace Tests\Feature;

use App\Models\BusLine;
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

        factory(LineOrder::class)->create(['station_id'=>1, 'next_station'=>2,'order'=>1]);
        $this->get('/api/v1/trips?start=tanta&end=cairo', [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

        ])->assertStatus(200);
    }

    public function testBookingApiValidation()
    {
        $response = $this->post(
            '/api/v1/trip/book/1',
            [
            'pickup_point'=> 3,
            'destination_point'=>4],
            [
            'Accept'=>'application/json',
            'Authorization'=> 'Bearer '.$this->token

            ]
        );
        $response->assertStatus(422)->assertExactJson([
            'message'=>'The given data was invalid.',
            'errors'=>[
                "id" => ["The selected id is invalid."],
                "destination_point"=>["The selected destination point is invalid."],
                'pickup_point'=> ['The selected pickup point is invalid.'],

            ]
        ]);
    }

    public function testBookingApiSucceeded()
    {
        factory(BusLine::class)->create();
        $response = $this->post(
            '/api/v1/trip/book/1',
            [
                'pickup_point'=> 1,
                'destination_point'=>2
            ],
            [
                'Accept'=>'application/json',
                'Authorization'=> 'Bearer '.$this->token
            ]
        );
        $this->assertDatabaseHas('bus_lines', ['pickup_id'=>1,'destination_id'=>2 ,'user_id'=>1]);
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
