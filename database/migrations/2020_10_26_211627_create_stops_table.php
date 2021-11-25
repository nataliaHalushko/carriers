<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('settlement_id')->unsigned();
            $table->string('lat');
            $table->string('lng');
            $table->string('name');
            $table->string('place_id');
            $table->timestamps();

            $table->foreign('settlement_id')
                ->references('id')->on('settlements')
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
        Schema::dropIfExists('stops');
    }
}
