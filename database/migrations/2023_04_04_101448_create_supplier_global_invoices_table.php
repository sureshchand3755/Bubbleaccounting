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
        Schema::create('supplier_global_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id');
            $table->string('invoice_no',150);
            $table->date('invoice_date');
            $table->string('ac_period',200);
            $table->text('reference');
            $table->string('net',200);
            $table->string('vat',200);
            $table->string('gross',200);
            $table->text('filename');
            $table->text('url');
            $table->integer('journal_id');
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
        Schema::dropIfExists('supplier_global_invoice');
    }
};
