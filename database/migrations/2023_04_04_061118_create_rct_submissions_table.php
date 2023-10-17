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
        Schema::create('rct_submission', function (Blueprint $table) {
            $table->increments('submisstio_id');
            $table->string('client_id',500);
            $table->longText('type');
            $table->longText('rct_id');
            $table->longText('principal_name');
            $table->longText('sub_contractor');
            $table->longText('sub_contractor_id');
            $table->longText('site');
            $table->longText('start_date');
            $table->longText('finish_date');
            $table->longText('value_gross');
            $table->longText('value_net');
            $table->longText('deduction');
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
        Schema::dropIfExists('rct_submission');
    }
};
