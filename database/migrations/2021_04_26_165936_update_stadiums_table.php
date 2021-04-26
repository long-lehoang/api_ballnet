<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStadiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stadiums', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropForeign('stadiums_id_owner_foreign');
            $table->dropColumn('id_owner');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stadiums', function (Blueprint $table) {
            $table->string('type');
            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')->references('id')->on('users');
        });
    }
}
