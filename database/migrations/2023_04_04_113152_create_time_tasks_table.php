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
        Schema::create('time_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('task_name');
            $table->text('clients');
            $table->integer('task_type');
            $table->integer('project_id');
            $table->string('practice_code',200);
            $table->integer('status');
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
        Schema::dropIfExists('time_tasks');
    }
};
