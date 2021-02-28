<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchJoiningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_joining', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_match')->constrained('match');
            $table->foreignId('id_player')->constrained('users');
            $table->foreignId('id_team')->constrained('team')->nullable();
            $table->foreignId('invited_by')->constrained('users')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('match_joining');
    }
}
