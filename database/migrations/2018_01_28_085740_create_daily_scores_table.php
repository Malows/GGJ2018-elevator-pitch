<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('score');
            $table->unsignedInteger('position')->nullable();
            $table->string('player');
            $table->unsignedInteger('influencer_id');
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
        Schema::dropIfExists('daily_scores');
    }
}
