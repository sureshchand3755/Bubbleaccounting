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
        Schema::create('opening_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id',100);
            $table->string('opening_balance',100);
            $table->date('opening_date');
            $table->integer('locked');
            $table->integer('journal_id');
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
        Schema::dropIfExists('opening_balance');
    }
};
