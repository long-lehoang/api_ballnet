<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceMatchJoiningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_match_joining', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_match_joining')->constrained('match_joining');
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->boolean('attendance')->nullable();
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
        Schema::dropIfExists('attendance_match_joining');
    }
}
