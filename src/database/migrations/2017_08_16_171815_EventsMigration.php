<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id',10);
            $table->string('name',80);
            $table->text('description')->nullable();
            $table->string('image',80)->nullable();
            $table->timestamps();

            $table->index(['event_id', 'name']);
        });

        Schema::create('event_volunteer',function(Blueprint $table){
            $table->integer('event_id')->unsigned();
            $table->integer('volunteer_id')->unsigned();

            $table->primary(['event_id', 'volunteer_id']);
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('volunteer_id')->references('volunteer_id')->on('volunteers')->onDelete('cascade');
            
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
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_volunteer');
    }
}
