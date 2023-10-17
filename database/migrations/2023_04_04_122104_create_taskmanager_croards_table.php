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
        Schema::create('taskmanager_croard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->string('client_id', 200);
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
        Schema::dropIfExists('taskmanager_croard');
    }
};
