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
            $table->increments('id',10);
            $table->string('name',80);
            $table->string('date',80);
            $table->text('description')->nullable();
            $table->string('image',80)->nullable();
            $table->timestamps();

            $table->index(['id', 'name']);
        });

        Schema::create('event_volunteer',function(Blueprint $table){
            $table->integer('event_id')->unsigned();
            $table->integer('volunteer_id')->unsigned();

            $table->primary(['event_id', 'volunteer_id']);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
            
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
