<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VolunteersMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->increments('id',10);
            $table->string('name',80);
            $table->text('description')->nullable();
            $table->string('status',80)->nullable();
            $table->string('image',80)->nullable();
            $table->timestamps();

            $table->index(['id', 'name']);
        });

        Schema::create('show_volunteer',function(Blueprint $table){
            $table->integer('show_id')->unsigned();
            $table->integer('volunteer_id')->unsigned();

            $table->primary(['show_id', 'volunteer_id']);
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
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
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('show_volunteer');
    }
}
