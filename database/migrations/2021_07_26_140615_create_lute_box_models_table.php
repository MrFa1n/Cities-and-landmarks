<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLuteBoxModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lute_box_models', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->boolean('active');
            $table->string('name');
            $table->string('desc');
            $table->json('extra');
            $table->string('tier');
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
        Schema::dropIfExists('lute_box_models');
    }
}
