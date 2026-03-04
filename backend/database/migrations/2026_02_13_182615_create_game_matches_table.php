<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tournament_id')
                ->constrained('tournaments')
                ->onDelete('cascade');
            $table->foreignId('team1_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreignId('team2_id')
                ->constrained('teams')
                ->onDelete('cascade');

            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            $table->dateTime('match_date');

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
        Schema::dropIfExists('game_matches');
    }
}
