<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteStopInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_stop_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('route_stop_id')->unsigned();
            $table->bigInteger('to_id')->unsigned();
            $table->integer('distance')->default(0);
            $table->float('price')->default(0);
            $table->timestamps();

            $table->foreign('route_stop_id')
                ->references('id')->on('route_stop')
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
        Schema::dropIfExists('routes');
    }
}
