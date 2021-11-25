<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bus_id')->unsigned();
            $table->string('number');
            $table->string('type');
            $table->boolean('removal')->default(0);
            $table->json('schedule')->nullable();
            $table->timestamps();

            $table->foreign('bus_id')
                ->references('id')->on('buses')
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
        Schema::dropIfExists('trips');
    }
}
