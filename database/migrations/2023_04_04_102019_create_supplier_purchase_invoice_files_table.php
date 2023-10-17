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
        Schema::create('supplier_purchase_invoice_files', function (Blueprint $table) {
            $table->increments('id');
            $table->text('filename');
            $table->text('url');
            $table->integer('supplier_id');
            $table->string('inv_date',150);
            $table->integer('ignore_file');
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
        Schema::dropIfExists('supplier_purchase_invoice_files');
    }
};
