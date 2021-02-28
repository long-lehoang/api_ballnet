<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_team', function (Blueprint $table) {
            $table->foreignID('id_team')->constrained('team');
            $table->foreignId('id_member')->constrained('users');
            $table->primary(['id_team','id_member']);
            $table->foreignId('invited_by')->constrained('users')->nullable();
            $table->string('status');
            $table->unsignedInteger('num_match');
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
        Schema::dropIfExists('member_team');
    }
}
