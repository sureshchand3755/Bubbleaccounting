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
        Schema::create('request_cheque_attached', function (Blueprint $table) {
            $table->increments('attached_id');
            $table->string('request_id',200);
            $table->string('cheque_id',200);
            $table->string('attachment',500);
            $table->text('specific_number');
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
        Schema::dropIfExists('request_cheque_attached');
    }
};