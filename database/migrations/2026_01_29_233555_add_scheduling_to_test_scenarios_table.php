<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_scenarios', function (Blueprint $table) {
            $table->string('frequency')->default('manual')->after('is_active'); // manual, hourly, daily, weekly
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_scenarios', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'last_run_at', 'next_run_at']);
        });
    }
};
