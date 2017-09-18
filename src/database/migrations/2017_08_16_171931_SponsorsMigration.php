<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SponsorsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80);
            $table->text('description')->nullable();
            $table->string('level',80);
            $table->string('status',80);
            $table->string('image',80)->nullable();
            $table->timestamps();
            $table->integer('volunteer_id')->unsigned()->nullable();

            $table->index(['id', 'name']);
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
        });

        Schema::create('event_sponsor',function(Blueprint $table){
            $table->integer('event_id')->unsigned();
            $table->integer('sponsor_id')->unsigned();

            $table->index(['event_id','sponsor_id']);
            $table->primary(['event_id', 'sponsor_id']);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('sponsor_id')->references('id')->on('sponsors')->onDelete('cascade');
            
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
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('event_sponsor');
    }
}
