<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusLineOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('line_id');
            $table->foreign('line_id')->references('id')->on('lines');

            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id')->references('id')->on('stations');

            $table->unsignedBigInteger('next_station')->nullable();
            $table->foreign('next_station')->references('id')->on('stations');

            $table->tinyInteger('order');

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
        Schema::dropIfExists('line_orders');
    }
}
