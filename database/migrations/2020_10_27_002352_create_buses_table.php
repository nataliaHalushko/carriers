<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('model_id')->unsigned();
            $table->bigInteger('schema_id')->unsigned();
            $table->bigInteger('carrier_id')->unsigned();
            $table->json('numbering')->nullable();
            $table->json('comfort')->nullable();
            $table->string('number');
            $table->integer('count_seat')->default(0);

            $table->timestamps();

            $table->foreign('carrier_id')
                ->references('id')->on('carriers')
                ->onDelete('cascade');
            $table->foreign('schema_id')
                ->references('id')->on('schemas')
                ->onDelete('cascade');
            $table->foreign('model_id')
                ->references('id')->on('models')
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
        Schema::dropIfExists('buses');
    }
}
