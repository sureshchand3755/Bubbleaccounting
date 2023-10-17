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
        Schema::create('task_job', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id',200);
            $table->string('user_id',200);
            $table->integer('active_id');
            $table->string('task_id',200);
            $table->integer('color');
            $table->time('start_time');
            $table->time('stop_time');
            $table->time('job_time');
            $table->date('job_date');
            $table->integer('job_type');
            $table->integer('quick_job');
            $table->integer('bulk_job');
            $table->integer('group_type');
            $table->integer('sub_group_type');
            $table->longText('selected_clients');
            $table->longText('selected_tasks');
            $table->string('minutes_per_client',100);
            $table->integer('round_type');
            $table->date('job_created_date');
            $table->text('comments');
            $table->timestamp('updated')->useCurrent();
            $table->integer('status');
            $table->integer('client_from_activelist');
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
        Schema::dropIfExists('task_job');
    }
};
