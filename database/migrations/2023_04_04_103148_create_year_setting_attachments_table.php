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
        Schema::create('year_setting_attachment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year_setting_id');
            $table->integer('check_file');
            $table->string('attachment',500);
            $table->text('url');
            $table->string('textval',20);
            $table->integer('textstatus');
            $table->integer('status');
            $table->integer('notes_type');
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
        Schema::dropIfExists('year_setting_attachment');
    }
};
