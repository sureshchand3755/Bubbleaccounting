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
        Schema::create('ta_excluded', function (Blueprint $table) {
            $table->increments('excluded_id');
            $table->string('excluded_client_id',500);
            $table->text('excluded_invoice');
            $table->integer('excluded_status');
            $table->timestamp('excluded_updatetime')->useCurrent();
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
        Schema::dropIfExists('ta_excluded');
    }
};
