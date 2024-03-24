<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update201 extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->char('uuid', 23)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
