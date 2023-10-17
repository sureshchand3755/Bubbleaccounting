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
        Schema::create('bubble_user', function (Blueprint $table) {
            $table->increments('id');
            $table->text('practice_name');
            $table->text('address1');
            $table->text('address2');
            $table->text('address3');
            $table->text('address4');
            $table->text('logo_name');
            $table->text('logo_url');
            $table->string('telephone_no', 150);
            $table->text('admin_user');
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
        Schema::dropIfExists('bubble_user');
    }
};
