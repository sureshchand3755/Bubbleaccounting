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
        Schema::create('cm_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->text('client_id');
            $table->string('client_added', 50);
            $table->text('firstname');
            $table->text('surname');
            $table->text('company');
            $table->text('address1');
            $table->text('address2');
            $table->text('address3');
            $table->text('address4');
            $table->text('address5');
            $table->text('email');
            $table->text('tye');
            $table->text('active');
            $table->text('tax_reg1');
            $table->text('tax_reg2');
            $table->text('tax_reg3');
            $table->text('email2');
            $table->text('phone');
            $table->text('linkcode');
            $table->text('cro');
            $table->text('ard');
            $table->text('trade_status');
            $table->text('directory');
            $table->string('employer_no', 50);
            $table->text('salutation');
            $table->string('statement', 12);
            $table->integer('send_statement');
            $table->text('notes');
            $table->text('practice_code');
            $table->integer('status');
            $table->timestamp('updatetime')->useCurrent();
            $table->timestamp('yearend_updatetime')->useCurrent();
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
        Schema::dropIfExists('cm_clients');
    }
};
