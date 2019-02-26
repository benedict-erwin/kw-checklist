<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->boolean('is_completed');
            $table->dateTime('due');
            $table->integer('urgency');
            $table->dateTime('completed_at');
            $table->string('last_update_by');
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
        Schema::dropIfExists('item');
    }
}
