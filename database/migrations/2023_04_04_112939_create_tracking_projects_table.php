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
        Schema::create('tracking_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->text('project_name');
            $table->dateTime('creation_date');
            $table->integer('tracking_type');
            $table->string('practice_code',200);
            $table->string('complex_value',100);
            $table->string('monthly_value',20);
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
        Schema::dropIfExists('tracking_projects');
    }
};
