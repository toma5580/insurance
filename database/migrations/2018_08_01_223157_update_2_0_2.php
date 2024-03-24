<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update202 extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::unprepared("ALTER TABLE `custom_fields` MODIFY `value` TEXT NULL DEFAULT NULL AFTER `uuid`");
        DB::unprepared("ALTER TABLE `policies` MODIFY `type` ENUM('annually','monthly','weekly') NOT NULL AFTER `special_remarks`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::unprepared("ALTER TABLE `custom_fields` MODIFY `value` TEXT NOT NULL AFTER `uuid`");
        DB::unprepared("ALTER TABLE `policies` MODIFY `type` ENUM('annually','monthly') NOT NULL AFTER `special_remarks`");
    }
}
