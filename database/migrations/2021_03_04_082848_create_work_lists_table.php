<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stage_list_id');
            $table->foreign('stage_list_id')->references('id')->on('stage_lists');
            $table->string('work_name');
            $table->integer('step_number');
            $table->boolean('check')->default(false);
            $table->date('date_start');
            $table->date('date_end');
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
        Schema::dropIfExists('work_lists');
    }
}
