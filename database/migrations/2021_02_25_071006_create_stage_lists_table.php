<?php

use App\Models\StageList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateStageListsTable
 */
class CreateStageListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('step_number');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('stage_name');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('stage_statuses');
            $table->boolean('check')->default(false);
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('stage_lists');
    }
}
