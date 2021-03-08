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
            $table->foreignID('team_id')->constrained('teams');
            $table->foreignId('member_id')->constrained('users');
            $table->primary(['team_id','member_id']);
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
