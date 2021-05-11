<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('dish_name');
            $table->string('seasoning');
            $table->string('memo')->nullable()->default('');
            $table->tinyInteger('public_private')->default(0);
            $table->tinyInteger('white')->default(0);
            $table->tinyInteger('pink')->default(0);
            $table->tinyInteger('red')->default(0);
            $table->tinyInteger('green')->default(0);
            $table->tinyInteger('yellowish_green')->default(0);
            $table->tinyInteger('yellow')->default(0);
            $table->tinyInteger('beige')->default(0);
            $table->tinyInteger('orange')->default(0);
            $table->tinyInteger('brown')->default(0);
            $table->tinyInteger('purple')->default(0);
            $table->tinyInteger('black')->default(0);
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
        Schema::dropIfExists('dishes');
    }
}
