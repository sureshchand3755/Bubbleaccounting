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
        Schema::create('vat_clients_compare', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 500);
            $table->string('ros_filer', 100);
            $table->string('tax_type', 100);
            $table->string('document_type', 100);
            $table->string('taxnumber', 100);
            $table->string('period', 100);
            $table->dateTime('due_date');
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
        Schema::dropIfExists('vat_clients_compare');
    }
};
