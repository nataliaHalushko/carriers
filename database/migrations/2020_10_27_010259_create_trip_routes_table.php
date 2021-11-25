<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_routes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trip_id')->unsigned();
            $table->integer('weekday')->nullable();
            $table->bigInteger('stop_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('arrival');
            $table->string('departure');

            $table->timestamps();

            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');

            $table->foreign('stop_id')
                ->references('id')->on('stops')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')->on('trip_routes')
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
