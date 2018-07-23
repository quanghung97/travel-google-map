<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WayPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('way_points', function (Blueprint $table) {
            $table->increments('id');
            $table->float('lat', 9, 6);
            $table->float('lng', 9, 6);
            $table->string('address');
            $table->string('action');
            $table->dateTime('leave_time');
            $table->dateTime('arrival_time');
            $table->integer('trip_id');
            $table->integer('order_num');
            $table->string('vehicle');
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
        Schema::dropIfExists('way_points');
    }
}
