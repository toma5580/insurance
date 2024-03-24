<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Emails extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->integer('recipient_id')->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->boolean('status');
            $table->string('subject', 64);
            $table->timestamp('created_at');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('emails');
    }
}
