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
        Schema::create('supplier_detail_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('global_id');
            $table->string('invoice_no',150);
            $table->text('description');
            $table->string('nominal_code',150);
            $table->string('net',150);
            $table->string('vat_rate',150);
            $table->string('vat_value',150);
            $table->string('gross',150);
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
        Schema::dropIfExists('supplier_detail_invoice');
    }
};
