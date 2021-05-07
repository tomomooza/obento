<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('ingredients_id');
            $table->tinyInteger('season1');
            $table->tinyInteger('season2');
            $table->tinyInteger('season3');
            $table->tinyInteger('season4');
            $table->tinyInteger('season5');
            $table->tinyInteger('season6');
            $table->tinyInteger('season7');
            $table->tinyInteger('season8');
            $table->tinyInteger('season9');
            $table->tinyInteger('season10');
            $table->tinyInteger('season11');
            $table->tinyInteger('season12');
            $table->string('memo')->nullable();
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
        Schema::dropIfExists('seasons');
    }
}
