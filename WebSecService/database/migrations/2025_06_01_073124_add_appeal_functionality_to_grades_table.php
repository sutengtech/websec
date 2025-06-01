<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->enum('appeal_status', ['none', 'pending', 'approved', 'rejected'])->default('none');
            $table->text('appeal_reason')->nullable();
            $table->timestamp('appealed_at')->nullable();
            $table->text('appeal_response')->nullable();
            $table->timestamp('appeal_responded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['appeal_status', 'appeal_reason', 'appealed_at', 'appeal_response', 'appeal_responded_at']);
        });
    }
};
