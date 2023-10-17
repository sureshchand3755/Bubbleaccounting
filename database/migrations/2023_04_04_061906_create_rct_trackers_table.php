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
        Schema::create('rct_tracker', function (Blueprint $table) {
            $table->increments('id');
            $table->text('client_id');
            $table->string('rct_type',2000);
            $table->text('subcontractor');
            $table->text('rctno');
            $table->text('reference');
            $table->date('date');
            $table->string('gross',20);
            $table->string('rate',20);
            $table->string('deduction',20);
            $table->decimal('net',$precision = 10, $scale = 2);
            $table->dateTime('email');
            $table->text('invoice');
            $table->integer('status');
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
        Schema::dropIfExists('rct_tracker');
    }
};
