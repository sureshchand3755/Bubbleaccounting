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
        Schema::create('paye_p30_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paye_task');
            $table->integer('year_id');
            $table->integer('month_id');
            $table->string('week1',20);
            $table->string('week2',20);
            $table->string('week3',20);
            $table->string('week4',20);
            $table->string('week5',20);
            $table->string('week6',20);
            $table->string('week7',20);
            $table->string('week8',20);
            $table->string('week9',20);
            $table->string('week10',20);
            $table->string('week11',20);
            $table->string('week12',20);
            $table->string('week13',20);
            $table->string('week14',20);
            $table->string('week15',20);
            $table->string('week16',20);
            $table->string('week17',20);
            $table->string('week18',20);
            $table->string('week19',20);
            $table->string('week20',20);
            $table->string('week21',20);
            $table->string('week22',20);
            $table->string('week23',20);
            $table->string('week24',20);
            $table->string('week25',20);
            $table->string('week26',20);
            $table->string('week27',20);
            $table->string('week28',20);
            $table->string('week29',20);
            $table->string('week30',20);
            $table->string('week31',20);
            $table->string('week32',20);
            $table->string('week33',20);
            $table->string('week34',20);
            $table->string('week35',20);
            $table->string('week36',20);
            $table->string('week37',20);
            $table->string('week38',20);
            $table->string('week39',20);
            $table->string('week40',20);
            $table->string('week41',20);
            $table->string('week42',20);
            $table->string('week43',20);
            $table->string('week44',20);
            $table->string('week45',20);
            $table->string('week46',20);
            $table->string('week47',20);
            $table->string('week48',20);
            $table->string('week49',20);
            $table->string('week50',20);
            $table->string('week51',20);
            $table->string('week52',20);
            $table->string('week53',20);
            $table->string('month1',20);
            $table->string('month2',20);
            $table->string('month3',20);
            $table->string('month4',20);
            $table->string('month5',20);
            $table->string('month6',20);
            $table->string('month7',20);
            $table->string('month8',20);
            $table->string('month9',20);
            $table->string('month10',20);
            $table->string('month11',20);
            $table->string('month12',20);
            $table->string('ros_liability',20);
            $table->string('task_liability',20);
            $table->string('liability_diff',20);
            $table->string('payments',20);
            $table->dateTime('last_email_sent');
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
        Schema::dropIfExists('paye_p30_periods');
    }
};
