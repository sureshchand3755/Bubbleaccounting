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
        Schema::create('year_client', function (Blueprint $table) {
            $table->increments('id');
            $table->text('year');
            $table->text('client_id');
            $table->text('setting_id');
            $table->text('setting_active');
            $table->text('setting_default');
            $table->timestamp('updatetime')->useCurrent();
            $table->text('distribution1_email');
            $table->text('distribution2_email');
            $table->text('distribution3_email');
            $table->timestamp('dist1_email_sent_date');
            $table->timestamp('dist2_email_sent_date');
            $table->timestamp('dist3_email_sent_date');
            $table->text('distribution1_future');
            $table->text('distribution2_future');
            $table->text('distribution3_future');
            $table->string('year_end_date',150);
            $table->integer('hide_na');
            $table->integer('notes_status');
            $table->longText('notes');
            $table->integer('status');
            $table->integer('disabled');
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
        Schema::dropIfExists('year_client');
    }
};
