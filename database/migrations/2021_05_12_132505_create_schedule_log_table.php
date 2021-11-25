<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('schedule_id')->unsigned();
            $table->bigInteger('stop_id')->unsigned();
            $table->integer('type')->default(0);
            $table->json('schema')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')
                ->references('id')->on('schedules')
                ->onDelete('cascade');
            $table->foreign('stop_id')
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
        Schema::dropIfExists('schedule_log');
    }
}
