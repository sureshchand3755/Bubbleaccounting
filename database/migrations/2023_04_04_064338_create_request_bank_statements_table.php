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
        Schema::create('request_bank_statement', function (Blueprint $table) {
            $table->increments('statement_id');
            $table->string('request_id',200);
            $table->string('bank_id',200);
            $table->string('statment_number',500);
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('status');
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
        Schema::dropIfExists('request_bank_statement');
    }
};
