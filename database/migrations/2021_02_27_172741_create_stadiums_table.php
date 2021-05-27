<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(1);
            $table->string('type');
            $table->string('sport');
            $table->string('location');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('phone');
            $table->float('rating')->default(0);
            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stadiums');
    }
}
