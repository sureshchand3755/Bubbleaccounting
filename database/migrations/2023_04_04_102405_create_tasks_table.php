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
        Schema::create('task', function (Blueprint $table) {
            $table->increments('task_id');
            $table->integer('task_created_id');
            $table->integer('task_year');
            $table->integer('task_week');
            $table->integer('task_month');
            $table->text('task_name');
            $table->integer('task_classified');
            $table->integer('enterhours');
            $table->integer('holiday');
            $table->integer('process');
            $table->integer('payslips');
            $table->integer('email');
            $table->integer('upload');
            $table->date('date');
            $table->text('users');
            $table->text('task_enumber');
            $table->string('task_email',300);
            $table->string('secondary_email',300);
            $table->text('salutation');
            $table->text('comments');
            $table->text('attached');
            $table->text('network');
            $table->dateTime('last_email_sent');
            $table->dateTime('last_email_sent_carry');
            $table->timestamp('updatetime')->useCurrent();
            $table->integer('notify_option');
            $table->integer('tasklevel');
            $table->integer('p30_pay');
            $table->integer('p30_email');
            $table->integer('task_status');
            $table->integer('task_started');
            $table->string('client_id',200);
            $table->integer('task_notify');
            $table->integer('task_complete_period');
            $table->integer('task_complete_period_type');
            $table->text('liability');
            $table->integer('rating');
            $table->integer('scheme_id');
            $table->integer('same_as_last');
            $table->integer('disclose_liability');
            $table->integer('distribute_email');
            $table->integer('bi_payroll');
            $table->integer('bi_payroll_next_status');
            $table->text('default_staff');
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
        Schema::dropIfExists('task');
    }
};
