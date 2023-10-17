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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->text('firstname');
            $table->text('lastname');
            $table->text('email');
            $table->text('password');
            $table->text('url');
            $table->text('filename');
            $table->integer('logins');
            $table->text('practice');
            $table->string('initial', 200);
            $table->date('park_date');
            $table->integer('park_status');
            $table->integer('user_status');
            $table->longText('table_content');
            $table->integer('header_row');
            $table->integer('secondary_row');
            $table->longText('source_titles');
            $table->longText('allocations');
            $table->integer('disabled');
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
        Schema::dropIfExists('user');
    }
};
