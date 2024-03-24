<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 256)->nullable()->default(null);
            $table->date('birthday')->nullable()->default(null);
            $table->decimal('commission_rate', 10, 2)->default(0);
            $table->integer('company_id')->unsigned();
            $table->string('email', 64)->unique();
            $table->string('first_name', 32);
            $table->integer('inviter_id')->unsigned()->nullable()->default(null);
            $table->string('last_name', 32)->nullable()->default(null);
            $table->char('locale', 5)->default(config('app.locale'));
            $table->string('password', 60);
            $table->string('phone', 16)->nullable()->default(null);
            $table->char('profile_image_filename', 19)->default('default-profile.jpg');
            $table->enum('role', array('admin', 'broker', 'client', 'staff', 'super'));
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }
}
