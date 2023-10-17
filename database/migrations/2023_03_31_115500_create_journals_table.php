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
        Schema::create('journals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('journal_date', 200);
            $table->string('connecting_journal_reference', 200);
            $table->string('reference', 200);
            $table->text('description');
            $table->string('nominal_code', 200);
            $table->string('dr_value', 200);
            $table->string('cr_value', 200);
            $table->string('journal_source', 200);
            $table->string('practice_code', 150);
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
        Schema::dropIfExists('journals');
    }
};
