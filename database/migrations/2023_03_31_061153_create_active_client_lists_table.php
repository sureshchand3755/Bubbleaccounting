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
        Schema::create('active_client_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 250);
            $table->integer('user_id');
            $table->string('practice_code', 250);
            $table->timestamp('created_date')->useCurrent();
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
        Schema::dropIfExists('active_client_list');
    }
};
