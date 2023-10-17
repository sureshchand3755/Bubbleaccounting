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
        Schema::create('rctclients', function (Blueprint $table) {
            $table->increments('client_id');
            $table->string('firstname',2000);
            $table->string('lastname',2000);
            $table->string('taxnumber',2000);
            $table->string('email',2000);
            $table->string('secondary_email',2000);
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
        Schema::dropIfExists('rctclients');
    }
};
