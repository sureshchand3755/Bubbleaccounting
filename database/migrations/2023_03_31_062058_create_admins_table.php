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
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100);
            $table->text('password');
            $table->text('pass_base');
            $table->string('email', 250);
            $table->text('notify_message');
            $table->text('distribute_link');
            $table->text('signature');
            $table->longText('croard_signature');
            $table->text('keydocs_cc_email');
            $table->text('keydocs_signature');
            $table->text('keydocs_header_image');
            $table->longText('payroll_signature');
            $table->text('cc_email');
            $table->string('task_cc_email', 255);
            $table->string('p30_cc_email', 255);
            $table->string('cm_cc_email', 255);
            $table->string('vat_cc_email', 255);
            $table->text('croard_cc_email');
            $table->integer('croard_submission_days');
            $table->text('payroll_cc_email');
            $table->text('delete_email');
            $table->text('cm_crypt');
            $table->string('view_pass', 255);
            $table->integer('central_locations');
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
        Schema::dropIfExists('admin');
    }
};
