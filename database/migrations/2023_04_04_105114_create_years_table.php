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
        Schema::create('year', function (Blueprint $table) {
            $table->increments('year_id');
            $table->text('year_name');
            $table->date('end_date');
            $table->integer('year_status');
            $table->integer('year_used');
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
        Schema::dropIfExists('year');
    }
};
