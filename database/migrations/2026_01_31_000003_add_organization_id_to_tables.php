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
        // Add organization_id to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });

        // Add organization_id to agents table
        Schema::table('agents', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });

        // Add organization_id to test_scenarios table
        Schema::table('test_scenarios', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });

        // Add organization_id to runs table
        Schema::table('runs', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('test_scenarios', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('runs', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
