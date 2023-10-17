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
        Schema::create('financial_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('connecting_referece_id', 200);
            $table->string('reference_id', 200);
            $table->string('journal_date', 200);
            $table->text('description');
            $table->string('debit_code', 200);
            $table->string('credit_code', 200);
            $table->string('debit_value', 200);
            $table->string('credit_value', 200);
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
        Schema::dropIfExists('financial_journals');
    }
};
