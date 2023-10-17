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
        Schema::create('aml_system', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 200);
            $table->integer('client_source');
            $table->string('client_source_detail', 200);
            $table->date('data_client');
            $table->integer('review');
            $table->date('review_date');
            $table->text('file_review');
            $table->integer('risk_category');
            $table->datetime('last_email_sent');
            $table->text('trade_details');
            $table->string('products_services', 200);
            $table->string('transaction_type', 200);
            $table->string('risk_factors', 200);
            $table->string('geo_area', 200);
            $table->integer('politically_exposed');
            $table->integer('high_risk');
            $table->datetime('updatetime')->useCurrent();
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
        Schema::dropIfExists('aml_system');
    }
};
