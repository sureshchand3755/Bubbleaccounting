<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskyear', function (Blueprint $table) {
            $table->increments('taskyear_id');
            $table->text('taskyear');
            $table->string('taskyear_day',200);
            $table->string('taskyear_month',200);
            $table->text('taskyear_user');
            $table->integer('taskyear_status');
            $table->integer('delete_status');
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
        Schema::dropIfExists('taskyear');
    }
};
