<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('starts')->default(Carbon::now());
            $table->timestamp('ends')->default(Carbon::now());
            $table->timestamp('registration_start')->default(Carbon::now());
            $table->timestamp('registration_end')->default(Carbon::now());
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('organizer_id')->unsigned();
            $table->integer('guest_capacity');
            $table->enum('event_type', ['type1', 'type2', 'type3']);
            $table->timestamps();
            
            $table->foreign('organizer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onUpdate('cascade')->onDelte('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
