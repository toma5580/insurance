<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientNotes extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('client_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->integer('subject_id')->unsigned();
            $table->integer('writer_id')->unsigned();
            $table->timestamp('created_at');
            $table->foreign('subject_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('writer_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('client_notes');
    }
}
