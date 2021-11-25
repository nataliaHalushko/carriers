<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trip_id')->unsigned();
            $table->bigInteger('carrier_id')->unsigned();
            $table->bigInteger('bus_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned();
            $table->integer('status')->default('0');
            $table->timestamp('date');

            $table->timestamps();

            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');

            $table->foreign('carrier_id')
                ->references('id')->on('carriers')
                ->onDelete('cascade');
            $table->foreign('bus_id')
                ->references('id')->on('buses')
                ->onDelete('cascade');
            $table->foreign('driver_id')
                ->references('id')->on('drivers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
