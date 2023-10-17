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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supplier_code',250);
            $table->string('supplier_name',250);
            $table->text('supplier_address');
            $table->text('web_url');
            $table->string('phone_no',50);
            $table->text('email');
            $table->text('iban');
            $table->text('bic');
            $table->text('vat_no');
            $table->text('currency');
            $table->string('opening_balance',100);
            $table->string('journal_id',200);
            $table->string('default_nominal',200);
            $table->text('username');
            $table->text('password');
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
        Schema::dropIfExists('suppliers');
    }
};
