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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('payments_id');
            $table->string('payment_date',200);
            $table->string('debit_nominal',200);
            $table->string('credit_nominal',200);
            $table->string('client_code',200);
            $table->integer('supplier_code');
            $table->text('debit_description');
            $table->text('comment');
            $table->string('amount',200);
            $table->integer('status');
            $table->integer('imported');
            $table->integer('hold_status');
            $table->date('clearance_date');
            $table->integer('journal_id');
            $table->integer('batch_id');
            $table->integer('error');
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
        Schema::dropIfExists('payments');
    }
};
