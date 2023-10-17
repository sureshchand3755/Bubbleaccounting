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
        Schema::create('ta_auto_allocation', function (Blueprint $table) {
            $table->increments('auto_id');
            $table->string('auto_client_id',500);
            $table->text('auto_invoice');
            $table->text('auto_tasks');
            $table->integer('auto_status');
            $table->timestamp('auto_updatetime')->useCurrent();
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
        Schema::dropIfExists('ta_auto_allocation');
    }
};
