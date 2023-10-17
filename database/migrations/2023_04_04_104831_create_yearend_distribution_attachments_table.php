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
        Schema::create('yearend_distribution_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('setting_id');
            $table->text('attachments');
            $table->text('url');
            $table->integer('distribution_type');
            $table->integer('attach_type');
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
        Schema::dropIfExists('yearend_distribution_attachments');
    }
};
