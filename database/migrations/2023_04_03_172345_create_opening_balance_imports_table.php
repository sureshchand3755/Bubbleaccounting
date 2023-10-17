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
        Schema::create('opening_balance_import', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session_id');
            $table->string('client_id',100);
            $table->string('invoice_id',100);
            $table->string('balance',250);
            $table->string('import_date',200);
            $table->integer('import_type');
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
        Schema::dropIfExists('opening_balance_import');
    }
};
