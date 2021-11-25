<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteStopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_stop', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('route_id')->unsigned();
            $table->bigInteger('stop_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('route_id')
                ->references('id')->on('routes')
                ->onDelete('cascade');

            $table->foreign('stop_id')
                ->references('id')->on('stops')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')->on('route_stop')
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
