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
        Schema::create('request_client', function (Blueprint $table) {
            $table->increments('request_id');
            $table->string('client_id',200);
            $table->string('category_id',200);
            $table->string('year',200);
            $table->date('request_date');
            $table->string('request_from',200);
            $table->dateTime('request_sent');
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
        Schema::dropIfExists('request_client');
    }
};
