<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('text', 500);
            $table->string('status');
            $table->unsignedBigInteger('stage_list_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('document_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('stage_list_id')->references('id')->on('stage_lists');
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
        Schema::dropIfExists('stage_reports');
    }
}
