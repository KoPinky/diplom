<?php

use App\Models\ApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name')->nullable();
            $table->timestamps();
        });

        ApplicationStatus::query()->create([
            'status_name' => 'Принят'
        ]);
        ApplicationStatus::query()->create([
            'status_name' => 'Отклонен'
        ]);
        ApplicationStatus::query()->create([
            'status_name' => 'Архив'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_statuses');
    }
}
