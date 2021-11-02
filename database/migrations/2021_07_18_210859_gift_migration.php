<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GiftMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donated_gift_models', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_id');
            $table->string('target_id');
            $table->string('description');
            $table->longText('icon');
            $table->string('type_of_gift');
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
        Schema::dropIfExists('gift_models');
    }
}
