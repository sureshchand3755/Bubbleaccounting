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
        Schema::create('vat_reviews_import_attachment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('import_id');
            $table->text('filename');
            $table->text('uploaded_filename');
            $table->text('url');
            $table->string('import_date', 120);
            $table->string('import_time', 120);
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
        Schema::dropIfExists('vat_reviews_import_attachment');
    }
};
