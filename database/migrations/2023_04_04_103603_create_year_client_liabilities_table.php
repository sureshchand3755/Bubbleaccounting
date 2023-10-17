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
        Schema::create('year_client_liability', function (Blueprint $table) {
            $table->increments('liability_id');
            $table->string('year_id',500);
            $table->string('client_id',500);
            $table->string('row_id',500);
            $table->string('setting_id',500);
            $table->text('liability1');
            $table->dateTime('liability1_updatetime');
            $table->text('liability2');
            $table->dateTime('liability2_updatetime');
            $table->text('liability3');
            $table->dateTime('liability3_updatetime');
            $table->text('payments');
            $table->text('balance');
            $table->text('prelim');
            $table->integer('date_filled');
            $table->string('yearend_date',500);
            $table->timestamp('updatetime')->useCurrent();
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
        Schema::dropIfExists('year_client_liability');
    }
};
