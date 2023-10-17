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
        Schema::create('aml_attachment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 200);
            $table->string('attachment', 500);
            $table->text('url');
            $table->text('standard_name');
            $table->integer('identity_type');
            $table->string('expiry_date', 10);
            $table->datetime('update_time')->useCurrent();
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
        Schema::dropIfExists('aml_attachment');
    }
};
