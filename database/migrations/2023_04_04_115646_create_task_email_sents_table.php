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
        Schema::create('task_email_sent', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_created_id');
            $table->integer('task_week');
            $table->integer('task_month');
            $table->integer('task_id');
            $table->dateTime('email_sent');
            $table->string('options',5);
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
        Schema::dropIfExists('task_email_sent');
    }
};
