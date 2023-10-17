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
        Schema::create('taskmanager_specifics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->longText('message');
            $table->integer('from_user');
            $table->integer('to_user');
            $table->date('created_date');
            $table->dateTime('allocated_date');
            $table->date('due_date');
            $table->date('park_date');
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
        Schema::dropIfExists('taskmanager_specifics');
    }
};
