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
        Schema::create('project_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('project_type');
            $table->string('client_id',200);
            $table->integer('project_status');
            $table->dateTime('project_date');
            $table->text('comment');
            $table->string('value',100);
            $table->string('month_1',100);
            $table->string('month_2',100);
            $table->string('month_3',100);
            $table->string('month_4',100);
            $table->string('month_5',100);
            $table->string('month_6',100);
            $table->string('month_7',100);
            $table->string('month_8',100);
            $table->string('month_9',100);
            $table->string('month_10',100);
            $table->string('month_11',100);
            $table->string('month_12',100);
            $table->string('month_13',100);
            $table->string('month_14',100);
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
        Schema::dropIfExists('project_clients');
    }
};
