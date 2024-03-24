<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Policies extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('policies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('beneficiaries')->nullable()->default(null);
            $table->date('expiry');
            $table->string('payer', 32);
            $table->decimal('premium', 10, 2);
            $table->integer('product_id')->unsigned();
            $table->char('ref_no', 8)->unique();
            $table->date('renewal');
            $table->text('special_remarks')->nullable();
            $table->enum('type', array('annually', 'monthly'));
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('policies');
    }
}
