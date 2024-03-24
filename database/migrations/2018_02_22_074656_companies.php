<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Companies extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 256)->nullable()->default(null);
            $table->string('aft_api_key', 64)->nullable()->default(null);
            $table->string('aft_username', 64)->nullable()->default(null);
            $table->enum('currency_code', collect(config('insura.currencies.list'))->map(function($currency) {
                return $currency['code'];
            })->toArray())->default(config('insura.currencies.default'));
            $table->text('custom_fields_metadata')->nullable()->default(null);
            $table->string('email', 64)->nullable()->default(null);
            $table->text('email_signature')->nullable()->default(null);
            $table->string('name', 64);
            $table->string('phone', 16)->nullable()->default(null);
            $table->text('product_categories')->nullable()->default(null);
            $table->text('product_sub_categories')->nullable()->default(null);
            $table->boolean('reminder_status')->default(false);
            $table->enum('text_provider', array('aft', 'twilio'))->nullable()->default(null);
            $table->string('text_signature', 32)->nullable()->default(null);
            $table->string('twilio_auth_token', 64)->nullable()->default(null);
            $table->string('twilio_number', 32)->nullable()->default(null);
            $table->string('twilio_sid', 64)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('companies');
    }
}
