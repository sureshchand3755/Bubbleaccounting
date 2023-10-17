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
        Schema::create('supplementary_manager', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number',200);
            $table->text('name');
            $table->text('description');
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
        Schema::dropIfExists('supplementary_manager');
    }
};
