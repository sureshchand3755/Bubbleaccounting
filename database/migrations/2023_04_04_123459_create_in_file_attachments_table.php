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
        Schema::create('in_file_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attach_id');
            $table->integer('file_id');
            $table->integer('check_file');
            $table->string('attachment', 500);
            $table->text('url');
            $table->string('textval', 20);
            $table->integer('b');
            $table->integer('p');
            $table->integer('s');
            $table->integer('o');
            $table->text('supplier');
            $table->string('code', 200);
            $table->string('date_attachment', 100);
            $table->string('percent_one', 100);
            $table->string('percent_two', 100);
            $table->string('percent_three', 100);
            $table->string('percent_four', 100);
            $table->string('percent_five', 100);
            $table->string('net', 100);
            $table->string('vat', 100);
            $table->string('gross', 100);
            $table->string('currency', 200);
            $table->string('value', 200);
            $table->integer('flag');
            $table->integer('status');
            $table->integer('secondary');
            $table->integer('textstatus');
            $table->integer('notes_type');
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
        Schema::dropIfExists('in_file_attachments');
    }
};
