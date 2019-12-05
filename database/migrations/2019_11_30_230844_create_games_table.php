<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount');
            $table->integer('odd');
            $table->integer('available_slots');
            $table->dateTime('time_played')->nullable();
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->timestamps();

            $table->foreign('winner_id')->references('id')->on('game_winners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
