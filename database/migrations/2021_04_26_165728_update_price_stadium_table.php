<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePriceStadiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_stadium', function (Blueprint $table) {
            $table->dropForeign('price_stadium_id_owner_foreign');
            $table->dropColumn('id_owner');
            $table->string('time')->change();
            $table->string('type');
            $table->foreignId('stadium_id')->constrained('stadiums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_stadium', function (Blueprint $table) {
            $table->dropColumn('stadium_id');
            $table->dropColumn('time');
            $table->datetime('time')->unique();
            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')->references('id')->on('users');
        });
    }
}
