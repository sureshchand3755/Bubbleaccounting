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
        Schema::create('paye_p30_year', function (Blueprint $table) {
            $table->increments('year_id');
            $table->text('year_name');
            $table->integer('year_status');
            $table->integer('year_used');
            $table->integer('show_active');
            $table->integer('week_from');
            $table->integer('week_to');
            $table->integer('month_from');
            $table->integer('month_to');
            $table->integer('selected_week_from');
            $table->integer('selected_week_to');
            $table->integer('selected_month_from');
            $table->integer('selected_month_to');
            $table->integer('active_month');
            $table->integer('disable_clients');
            $table->integer('email_clients');
            $table->timestamp('updatetime')->useCurrent();
            $table->integer('delete_status');
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
        Schema::dropIfExists('paye_p30_year');
    }
};
