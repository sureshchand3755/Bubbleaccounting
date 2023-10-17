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
        Schema::create('p30_month', function (Blueprint $table) {
            $table->increments('month_id');
            $table->string('year',200);
            $table->string('month',200);
            $table->dateTime('month_closed');
            $table->timestamp('updatetime')->useCurrent();
            $table->integer('month_status');
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
        Schema::dropIfExists('p30_month');
    }
};
