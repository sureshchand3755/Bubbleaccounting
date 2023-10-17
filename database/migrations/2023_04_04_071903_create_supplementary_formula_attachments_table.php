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
        Schema::create('supplementary_formula_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supple_id',200);
            $table->integer('formula_id');
            $table->text('name');
            $table->text('value_1');
            $table->text('value_1_description');
            $table->text('value_2');
            $table->text('value_2_description');
            $table->integer('value_3');
            $table->text('value_3_number');
            $table->text('value_3_combo1');
            $table->text('value_3_combo2');
            $table->text('value_3_formula');
            $table->text('value_3_output');
            $table->text('value_3_description');
            $table->integer('value_4');
            $table->text('value_4_number');
            $table->text('value_4_combo1');
            $table->text('value_4_combo2');
            $table->text('value_4_formula');
            $table->text('value_4_output');
            $table->text('value_4_description');
            $table->integer('value_5');
            $table->text('value_5_number');
            $table->text('value_5_combo1');
            $table->text('value_5_combo2');
            $table->text('value_5_formula');
            $table->text('value_5_output');
            $table->text('value_5_description');
            $table->integer('value_6');
            $table->text('value_6_number');
            $table->text('value_6_combo1');
            $table->text('value_6_combo2');
            $table->text('value_6_formula');
            $table->text('value_6_output');
            $table->text('value_6_description');
            $table->text('invoice_number');
            $table->text('fixed_text');
            $table->text('magic_text');
            $table->text('supplementary_text');
            $table->text('load_id');
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
        Schema::dropIfExists('supplementary_formula_attachments');
    }
};
