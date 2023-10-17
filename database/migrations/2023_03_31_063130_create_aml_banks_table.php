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
        Schema::create('aml_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 200);
            $table->string('bank_name', 200);
            $table->string('account_name', 200);
            $table->string('account_number', 200);
            $table->datetime('updatetime')->useCurrent();
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
        Schema::dropIfExists('aml_bank');
    }
};
