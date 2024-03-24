<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;

class Update204 extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Set engine to InnoDB
        DB::statement('ALTER TABLE `attachments` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `chats` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `client_notes` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `companies` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `custom_fields` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `emails` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `payments` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `policies` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `products` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `reminders` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `texts` ENGINE = InnoDB');
        DB::statement('ALTER TABLE `users` ENGINE = InnoDB');
        // Add foreign keys
        try {
            Schema::table('reminders', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('attachments', function (Blueprint $table) {
                $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('chats', function (Blueprint $table) {
                $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('client_notes', function (Blueprint $table) {
                $table->foreign('subject_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('writer_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('emails', function (Blueprint $table) {
                $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('texts', function (Blueprint $table) {
                $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('policies', function (Blueprint $table) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
        } catch(QueryException $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        
    }
}
