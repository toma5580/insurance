<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Attachments extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attachee_id')->unsigned();
            $table->string('attachee_type', 32);
            $table->string('filename', 16);
            $table->string('name', 32);
            $table->integer('uploader_id')->unsigned();
            $table->timestamp('created_at');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('attachments');
    }
}