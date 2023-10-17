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
        Schema::create('vat_review_settings', function (Blueprint $table) {
            $table->id();
            $table->text('subject')
            $table->integer('period_end')->default(0)
            $table->longText('note')
            $table->integer('breakdown')->default(0)
            $table->text('signature')
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
        Schema::dropIfExists('vat_review_settings');
    }
};
