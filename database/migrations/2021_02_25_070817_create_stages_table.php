<?php

use App\Models\Stage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateStagesTable
 */
class CreateStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('stage_name');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->timestamps();
        });
        Stage::query()->create([
            'stage_name' => 'Демонтаж',
            'service_id' => 1
        ]);
        Stage::query()->create([
            'stage_name' => 'Подготовка к работе',
            'service_id' => 1
        ]);
        Stage::query()->create([
            'stage_name' => 'Работа',
            'service_id' => 1
        ]);
        Stage::query()->create([
            'stage_name' => 'Завершение работ',
            'service_id' => 1
        ]);


        Stage::query()->create([
            'stage_name' => 'Этап 1 копитал',
            'service_id' => 2
        ]);
        Stage::query()->create([
            'stage_name' => 'Этап 2 копитал',
            'service_id' => 2
        ]);
        Stage::query()->create([
            'stage_name' => 'Этап 3 копитал',
            'service_id' => 2
        ]);
        Stage::query()->create([
            'stage_name' => 'Этап 4 копитал',
            'service_id' => 2
        ]);
        Stage::query()->create([
            'stage_name' => 'Этап 5 копитал',
            'service_id' => 2
        ]);
        Stage::query()->create([
            'stage_name' => 'Этап 6 копитал',
            'service_id' => 2
        ]);

        Stage::query()->create([
            'stage_name' => 'Подготовка десигна',
            'service_id' => 3
        ]);
        Stage::query()->create([
            'stage_name' => 'Демонтаж старого десигна',
            'service_id' => 3
        ]);
        Stage::query()->create([
            'stage_name' => 'Наклейка нового десигна',
            'service_id' => 3
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stages');
    }
}
