<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('carrier_id')->unsigned();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('surname');
            $table->string('licence');
            $table->timestamp('date_licence');
            $table->json('category')->nullable();
            $table->timestamp('date_medical')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->foreign('carrier_id')
                ->references('id')->on('carriers')
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
        Schema::dropIfExists('drivers');
    }
}
