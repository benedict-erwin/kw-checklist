<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist', function (Blueprint $table) {
            $table->increments('id');
            $table->string('object_domain', 100);
            $table->integer('object_id');
            $table->string('description', 1000);
            $table->boolean('is_completed');
            $table->dateTime('completed_at');
            $table->boolean('updated_by');
            $table->dateTime('due');
            $table->integer('urgency');
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
        Schema::dropIfExists('checklist');
    }
}
