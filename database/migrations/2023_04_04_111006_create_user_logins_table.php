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
        Schema::create('user_login', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid');
            $table->string('username', 2000);
            $table->text('password');
            $table->integer('week_incomplete');
            $table->integer('month_incomplete');
            $table->integer('cm_incomplete');
            $table->integer('aml_incomplete');
            $table->integer('p30_incomplete');
            $table->integer('p30_na');
            $table->integer('yearend_incomplete');
            $table->integer('infile_incomplete');
            $table->integer('infile_inactive');
            $table->integer('week1_hide');
            $table->integer('week2_hide');
            $table->integer('week3_hide');
            $table->integer('week4_hide');
            $table->integer('week5_hide');
            $table->integer('week6_hide');
            $table->integer('week7_hide');
            $table->integer('week8_hide');
            $table->integer('week9_hide');
            $table->integer('week10_hide');
            $table->integer('week11_hide');
            $table->integer('week12_hide');
            $table->integer('week13_hide');
            $table->integer('week14_hide');
            $table->integer('week15_hide');
            $table->integer('week16_hide');
            $table->integer('week17_hide');
            $table->integer('week18_hide');
            $table->integer('week19_hide');
            $table->integer('week20_hide');
            $table->integer('week21_hide');
            $table->integer('week22_hide');
            $table->integer('week23_hide');
            $table->integer('week24_hide');
            $table->integer('week25_hide');
            $table->integer('week26_hide');
            $table->integer('week27_hide');
            $table->integer('week28_hide');
            $table->integer('week29_hide');
            $table->integer('week30_hide');
            $table->integer('week31_hide');
            $table->integer('week32_hide');
            $table->integer('week33_hide');
            $table->integer('week34_hide');
            $table->integer('week35_hide');
            $table->integer('week36_hide');
            $table->integer('week37_hide');
            $table->integer('week38_hide');
            $table->integer('week39_hide');
            $table->integer('week40_hide');
            $table->integer('week41_hide');
            $table->integer('week42_hide');
            $table->integer('week43_hide');
            $table->integer('week44_hide');
            $table->integer('week45_hide');
            $table->integer('week46_hide');
            $table->integer('week47_hide');
            $table->integer('week48_hide');
            $table->integer('week49_hide');
            $table->integer('week50_hide');
            $table->integer('week51_hide');
            $table->integer('week52_hide');
            $table->integer('week53_hide');
            $table->integer('month1_hide');
            $table->integer('month2_hide');
            $table->integer('month3_hide');
            $table->integer('month4_hide');
            $table->integer('month5_hide');
            $table->integer('month6_hide');
            $table->integer('month7_hide');
            $table->integer('month8_hide');
            $table->integer('month9_hide');
            $table->integer('month10_hide');
            $table->integer('month11_hide');
            $table->integer('month12_hide');
            $table->integer('paye_hide_task');
            $table->integer('paye_hide_columns');
            $table->string('opening_balance_date', 200);
            $table->integer('taskmanager_view');
            $table->string('statement_monthly_from_period', 100);
            $table->string('statement_monthly_to_period', 100);
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
        Schema::dropIfExists('user_login');
    }
};
