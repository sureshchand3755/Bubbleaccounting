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
        Schema::create('qba', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('table_content');
            $table->integer('header_row');
            $table->integer('secondary_row');
            $table->longText('source_titles');
            $table->longText('allocations');
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
        Schema::dropIfExists('qba');
    }
};
