<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomFields extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 64);
            $table->integer('model_id')->unsigned();
            $table->string('model_type', 32);
            $table->enum('type', array('checkbox','date','email','hidden','number','select','tel','text','textarea'));
            $table->text('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('custom_fields');
    }
}
