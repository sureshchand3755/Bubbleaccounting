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
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_id');
            $table->date('rec_date');
            $table->string('stmt_bal',100);
            $table->date('stmt_date');
            $table->string('total_os_items',100);
            $table->text('rec_attached_dir');
            $table->text('rec_attached_file');
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
        Schema::dropIfExists('reconciliations');
    }
};
