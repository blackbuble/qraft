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
        Schema::create('test_flakiness', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_scenario_id')->constrained()->onDelete('cascade');
            $table->integer('flakiness_score')->default(0)->comment('0-100 score, higher = more flaky');
            $table->integer('total_runs')->default(0);
            $table->integer('pass_count')->default(0);
            $table->integer('fail_count')->default(0);
            $table->integer('transition_count')->default(0)->comment('Number of pass/fail transitions');
            $table->json('pattern')->nullable()->comment('Detected failure patterns');
            $table->timestamp('last_analyzed_at')->nullable();
            $table->text('ai_diagnosis')->nullable()->comment('AI-generated root cause');
            $table->text('suggested_fix')->nullable()->comment('AI-generated fix suggestion');
            $table->timestamps();

            $table->index('flakiness_score');
            $table->index('test_scenario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_flakiness');
    }
};
