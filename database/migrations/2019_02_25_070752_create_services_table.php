<?php

use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateServicesTable
 */
class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Service::query()->create([
            'service_name' => 'косметический ремонт',
            'price' => '2000',
            'description' => 'Будет выполнен косметический ремонт'
        ]);
        Service::query()->create([
            'service_name' => 'капитальный ремонт',
            'price' => '5000',
            'description' => 'Будет выполнен капитальный ремонт'
        ]);
        Service::query()->create([
            'service_name' => 'дизайнерский ремонт',
            'price' => '12000',
            'description' => 'Будет выполнен дизайнерский ремонт'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
