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
        Schema::create('user_calculate_cost', function (Blueprint $table) {
            $table->increments('cost_id');
            $table->string('user_id', 500);
            $table->string('base_salary', 500);
            $table->string('annual_bonus', 500);
            $table->string('other_annual', 500);
            $table->string('total_salary', 500);
            $table->string('standard_hour', 500);
            $table->string('holiday_day', 500);
            $table->string('rate_social_insurance', 500);
            $table->string('social_insurance', 500);
            $table->string('cost_per_hour', 500);
            $table->string('holiday_cost_per_hour', 500);
            $table->string('final_cost_per_hour', 500);
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
        Schema::dropIfExists('user_calculate_cost');
    }
};
