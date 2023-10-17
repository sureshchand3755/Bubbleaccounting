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
        Schema::create('central_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('client_management');
            $table->text('invoice_management');
            $table->text('client_statements');
            $table->text('weekly_monthly');
            $table->text('p30');
            $table->text('vat');
            $table->text('rct');
            $table->text('year_end');
            $table->text('time_location');
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
        Schema::dropIfExists('central_locations');
    }
};
