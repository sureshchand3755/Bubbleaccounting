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
        Schema::create('payroll_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id',200);
            $table->string('task_id',200);
            $table->string('year',200);
            $table->string('month',200);
            $table->string('week',200);
            $table->dateTime('email_sent');
            $table->timestamp('updatetime')->useCurrent();
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
        Schema::dropIfExists('payroll_tasks');
    }
};
