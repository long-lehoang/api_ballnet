<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadium', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status');
            $table->string('type');
            $table->string('sport');
            $table->float('rating')->nullable();
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
        Schema::dropIfExists('stadium');
    }
}
