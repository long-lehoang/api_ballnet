<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceStadiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_stadium', function (Blueprint $table) {
            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')->references('id')->on('users');
            $table->datetime('time')->unique();
            $table->primary(['id_owner','time']);
            $table->unsignedBigInteger('price');
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
        Schema::dropIfExists('price_stadium');
    }
}
