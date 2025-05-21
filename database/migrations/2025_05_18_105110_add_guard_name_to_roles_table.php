<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('roles', function ($table) {
        $table->string('guard_name')->default('web')->after('name');
    });
}

public function down()
{
    Schema::table('roles', function ($table) {
        $table->dropColumn('guard_name');
    });
}
};
