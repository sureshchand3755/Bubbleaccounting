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
        Schema::create('p30_task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('task_year');
            $table->integer('task_month');
            $table->text('task_name');
            $table->string('task_level', 300);
            $table->string('liability', 300);
            $table->integer('pay');
            $table->string('task_period', 300);
            $table->integer('email');
            $table->integer('users');
            $table->integer('task_classified');
            $table->date('date');
            $table->text('task_enumber');
            $table->string('task_email', 300);
            $table->string('secondary_email', 300);
            $table->text('salutation');
            $table->text('attached');
            $table->text('network');
            $table->dateTime('last_email_sent');
            $table->timestamp('updatetime')->useCurrent();
            $table->integer('task_status');
            $table->integer('na');
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
        Schema::dropIfExists('p30_task');
    }
};
