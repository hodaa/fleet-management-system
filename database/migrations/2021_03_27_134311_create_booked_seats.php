<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedSeats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_seats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bus_id');
            $table->foreign('bus_id')->references('id')->on('buses');

            $table->unsignedBigInteger('pickup_id')->nullable();
            $table->foreign('pickup_id')->references('id')->on('stations');

            $table->unsignedBigInteger('destination_id')->nullable();
            $table->foreign('destination_id')->references('id')->on('stations');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_seats');
    }
}
