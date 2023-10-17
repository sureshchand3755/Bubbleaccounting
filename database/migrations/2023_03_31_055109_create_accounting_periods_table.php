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
        Schema::create('accounting_period', function (Blueprint $table) {
            $table->increments('accounting_id');
            $table->date('ac_start_date');
            $table->date('ac_end_date');
            $table->text('ac_description');
            $table->integer('status');
            $table->integer('ac_lock');
            $table->datetime('updatetime')->useCurrent();
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
        Schema::dropIfExists('accounting_period');
    }
};
