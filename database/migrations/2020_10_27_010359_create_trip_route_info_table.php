<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripRouteInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_route_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trip_route_id')->unsigned();
            $table->bigInteger('to_id')->unsigned()->nullable();
            $table->integer('distance')->default(0);
            $table->float('price')->default(0);
            $table->timestamps();

            $table->foreign('trip_route_id')
                ->references('id')->on('trip_routes')
                ->onDelete('cascade');

            $table->foreign('to_id')
                ->references('id')->on('stops')
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
        Schema::dropIfExists('trip_routes');
    }
}
