<?php

use App\Models\ObjectB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateObjectsTable
 */
class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->integer('amountRoom')->nullable();
            $table->integer('area')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('object_statuses');
            $table->timestamps();
        });

        ObjectB::query()->create([
            'address' => 'ул. Клюева 3',
            'amountRoom' => 2,
            'area' => 52,
            'description' => 'no comment',
            'status_id' => 1
        ]);

        ObjectB::query()->create([
            'address' => 'ул. Останкино 9',
            'amountRoom' => 2,
            'area' => 52,
            'description' => 'no comment',
            'status_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objects');
    }
}
