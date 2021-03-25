<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date_start');
            $table->date('date_end');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->double('price', 12, 2)->nullable();
            $table->unsignedBigInteger('object_id');
            $table->foreign('object_id')->references('id')->on('objects');
            $table->timestamps();
        });

        Order::query()->create([
            'date_contract' => '2020-12-12',
            'date_start' => '2020-12-12',
            'date_end' => '2020-12-12',
            'status' => 'В процессе',
            'service_id' => 1,
            'object_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

