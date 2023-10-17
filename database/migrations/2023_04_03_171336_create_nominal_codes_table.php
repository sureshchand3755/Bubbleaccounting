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
        Schema::create('nominal_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',200);
            $table->text('description');
            $table->text('primary_group');
            $table->text('debit_group');
            $table->text('credit_group');
            $table->integer('type');
            $table->string('practice_code',150);
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
        Schema::dropIfExists('nominal_codes');
    }
};
