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
        Schema::create('financial_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name', 300);
            $table->string('account_name', 300);
            $table->string('account_number', 300);
            $table->text('description');
            $table->string('nominal_code', 150);
            $table->string('debit_balance', 200);
            $table->string('credit_balance', 200);
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
        Schema::dropIfExists('financial_banks');
    }
};
