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
        Schema::create('vat_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 200);
            $table->string('month_year', 200);
            $table->integer('type');
            $table->text('filename');
            $table->text('url');
            $table->string('t1', 80);
            $table->string('t2', 80);
            $table->string('t1_value', 200);
            $table->string('t2_value', 200);
            $table->text('comments');
            $table->integer('approve_status');
            $table->string('reg', 80);
            $table->text('textval');
            $table->string('from_period', 100);
            $table->string('to_period', 100);
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
        Schema::dropIfExists('vat_reviews');
    }
};
