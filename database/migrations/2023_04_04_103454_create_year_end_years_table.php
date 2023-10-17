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
        Schema::create('year_end_year', function (Blueprint $table) {
            $table->increments('id');
            $table->text('year');
            $table->text('setting_id');
            $table->text('setting_active');
            $table->text('setting_default');
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
        Schema::dropIfExists('year_end_year');
    }
};
