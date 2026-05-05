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
        Schema::create('task_completion_systems', function (Blueprint $table) {
            $table->id();

            // Student and Form references
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();

            // Timeline
            $table->dateTime('assigned_at');
            $table->date('deadline');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Status and Progress
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'completed', 'overdue'])->default('in_progress');
            $table->integer('completion_percentage')->default(0);

            // Remarks
            $table->longText('admin_remarks')->nullable();
            $table->longText('overall_remarks')->nullable();
            $table->longText('student_response_to_remarks')->nullable();

            $table->timestamps();
        });

        // Create task completion remarks table for per-question remarks
        Schema::create('task_completion_remarks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_completion_id')->constrained('task_completion_systems')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();

            $table->longText('admin_remark')->nullable();
            $table->longText('student_response')->nullable();
            $table->dateTime('responded_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_completion_remarks');
        Schema::dropIfExists('task_completion_systems');
    }
};
