<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('bus_no');
            $table->integer('seat_no')->unique();
            $table->integer('line_id');

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
        Schema::dropIfExists('bus_lines');
    }
}
