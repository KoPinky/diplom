<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('first_name');
            $table->string('phone');
            $table->unsignedBigInteger('experience')->nullable();
            $table->string('specialization')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('second_name')->nullable();
            $table->string('email')->nullable(false)->unique('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        User::query()->create([
            'email' => 'email@mail.com',
            'first_name' => 'Головенькин',
            'password' => Hash::make('4kR%UHy'),
            'phone' => '89521504899',
            'role_id' => '1',
            'second_name' => 'Александрович',
            'surname' => 'Константин',
        ]);
        User::query()->create([
            'email' => '36mwq@mail.com',
            'first_name' => 'Степнов',
            'password' => Hash::make('9sS@Urh'),
            'phone' => '89523691525',
            'role_id' => '4',
            'second_name' => 'Витальевич',
            'surname' => 'Александр',
        ]);
        User::query()->create([
            'email' => 'ub2gl@mail.com',
            'first_name' => 'Ахметова',
            'password' => Hash::make('8uW@4nob'),
            'phone' => '89631582653',
            'role_id' => '2',
            'second_name' => 'Алексеевна',
            'surname' => 'Елена'
        ]);
        User::query()->create([
            'email' => 'nh2gl@mail.com',
            'first_name' => 'Парфенов',
            'password' => Hash::make('9jX@cEL'),
            'phone' => '89235698100',
            'role_id' => '3',
            'second_name' => 'Геннадьевич',
            'surname' => 'Леонид'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
