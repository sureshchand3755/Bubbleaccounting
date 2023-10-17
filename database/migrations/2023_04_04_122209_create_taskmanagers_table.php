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
        Schema::create('taskmanager', function (Blueprint $table) {
            $table->increments('id');
            $table->string('taskid', 25);
            $table->integer('author');
            $table->text('author_email');
            $table->date('creation_date');
            $table->integer('allocated_to');
            $table->text('allocate_email');
            $table->integer('internal');
            $table->integer('task_type');
            $table->string('client_id', 250);
            $table->text('subject');
            $table->longText('task_specifics');
            $table->date('due_date');
            $table->integer('project_id');
            $table->string('project_hours', 50);
            $table->string('project_mins', 50);
            $table->integer('recurring_task');
            $table->integer('recurring_days');
            $table->integer('retain_specifics');
            $table->integer('retain_files');
            $table->integer('progress');
            $table->integer('avoid_email');
            $table->integer('author_spec_status');
            $table->integer('allocated_spec_status');
            $table->date('park_date');
            $table->integer('auto_close');
            $table->integer('two_bill');
            $table->integer('billing_status');
            $table->string('invoice', 250);
            $table->integer('user_rating');
            $table->string('practice_code', 25);
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
        Schema::dropIfExists('taskmanager');
    }
};
