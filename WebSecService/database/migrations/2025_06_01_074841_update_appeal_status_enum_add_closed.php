<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Update the enum to include 'closed' status
            DB::statement("ALTER TABLE grades MODIFY COLUMN appeal_status ENUM('none', 'pending', 'approved', 'rejected', 'closed') DEFAULT 'none'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Revert back to original enum values
            DB::statement("ALTER TABLE grades MODIFY COLUMN appeal_status ENUM('none', 'pending', 'approved', 'rejected') DEFAULT 'none'");
        });
    }
};
