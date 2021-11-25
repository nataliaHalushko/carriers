<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('device_id')->nullable();
            $table->bigInteger('schedules_id')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('from_id')->unsigned();
            $table->bigInteger('to_id')->unsigned();

            $table->string('fname');
            $table->string('lname');
            $table->string('phone');
            $table->string('email');
            $table->string('qr')->nullable();

            $table->integer('status')->default(0);

            $table->float('price',10,2);
            $table->integer('seat');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('from_id')
                ->references('id')->on('stops')
                ->onDelete('cascade');

            $table->foreign('to_id')
                ->references('id')->on('stops')
                ->onDelete('cascade');

            $table->foreign('schedules_id')
                ->references('id')->on('schedules')
                ->onDelete('cascade');

            $table->foreign('order_id')
                ->references('id')->on('orders')
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
        Schema::dropIfExists('tickets');
    }
}
