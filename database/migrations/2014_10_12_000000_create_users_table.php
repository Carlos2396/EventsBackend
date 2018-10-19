<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('password');
            $table->string('phone', 13);
            $table->string('alias', 100)->nullable();
            $table->string('image')->nullable();
            $table->uuid('confirmation_code');
            $table->timestamp('confirmed_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
