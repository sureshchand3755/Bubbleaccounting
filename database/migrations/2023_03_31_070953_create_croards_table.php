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
        Schema::create('croard', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 255);
            $table->text('company_name');
            $table->string('cro_ard', 200);
            $table->longText('notes');
            $table->text('filename');
            $table->text('url');
            $table->text('signature');
            $table->string('signature_date', 200);
            $table->string('rbo_submission', 25);
            $table->string('last_email_sent', 200);
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
        Schema::dropIfExists('croard');
    }
};
