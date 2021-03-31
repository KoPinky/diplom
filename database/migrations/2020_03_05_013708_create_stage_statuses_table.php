<?php

use App\Models\StageStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name')->nullable();
            $table->timestamps();
        });

        StageStatus::query()->create([
            'status_name' => 'Не начат'
        ]);
        StageStatus::query()->create([
            'status_name' => 'В работе'
        ]);
        StageStatus::query()->create([
            'status_name' => 'Ожидает проверки'
        ]);
        StageStatus::query()->create([
            'status_name' => 'Завершён'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stage_statuses');
    }
}
