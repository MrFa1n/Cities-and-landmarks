<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LuteBoxMigrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lutebox_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->float('price');
            $table->string('type_of_lutebox');
            $table->json('extra');
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
        Schema::dropIfExists('lutebox_models');
    }
}
