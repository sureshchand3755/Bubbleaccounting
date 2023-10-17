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
        Schema::create('task_attached', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->string('attachment',500);
            $table->text('url');
            $table->integer('network_attach');
            $table->integer('copied');
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
        Schema::dropIfExists('task_attached');
    }
};
