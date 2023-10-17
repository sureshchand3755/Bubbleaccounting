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
        Schema::create('vat_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('taxnumber');
            $table->text('pemail');
            $table->text('semail');
            $table->text('salutation');
            $table->text('self_manage');
            $table->string('always_nil', 20);
            $table->text('ros_filer');
            $table->text('tax_type');
            $table->text('document_type');
            $table->text('period');
            $table->date('due_date');
            $table->dateTime('last_email_sent');
            $table->integer('status');
            $table->string('cm_client_id', 200);
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
        Schema::dropIfExists('vat_clients');
    }
};
