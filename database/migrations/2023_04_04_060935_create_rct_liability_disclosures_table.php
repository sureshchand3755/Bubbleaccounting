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
        Schema::create('rct_liability_disclosure', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id',150);
            $table->integer('email_monthly_disclosure');
            $table->integer('pay_liability');
            $table->integer('pay_from_rct');
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
        Schema::dropIfExists('rct_liability_disclosure');
    }
};
