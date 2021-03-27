<?php

namespace Tests\Feature;

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
        factory(User::class)->create(['email'=>'user@gmail.com','password'=>Hash::make('12345678')]);

        $user = factory(User::class)->create();

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
}
