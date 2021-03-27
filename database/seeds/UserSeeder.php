<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'=> 'hoda',
                'email' => 'user@fleet.com',
                'password' => Hash::make(12345678)]
        ]);
    }
}
