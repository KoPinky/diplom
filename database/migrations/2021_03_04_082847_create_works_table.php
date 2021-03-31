<?php

use App\Models\Work;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('stages');
            $table->string('work_name');
            $table->timestamps();
        });

        Work::query()->create([
            'stage_id' => 1,
            'work_name' => '1'
        ]);
        Work::query()->create([
            'stage_id' => 1,
            'work_name' => '2'
        ]);
        Work::query()->create([
            'stage_id' => 2,
            'work_name' => '2.1'
        ]);
        Work::query()->create([
            'stage_id' => 2,
            'work_name' => '2.2'
        ]);
        Work::query()->create([
            'stage_id' => 3,
            'work_name' => '3.1'
        ]);
        Work::query()->create([
            'stage_id' => 3,
            'work_name' => '3.2'
        ]);
        Work::query()->create([
            'stage_id' => 4,
            'work_name' => '4.1'
        ]);
        Work::query()->create([
            'stage_id' => 4,
            'work_name' => '4.2'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
