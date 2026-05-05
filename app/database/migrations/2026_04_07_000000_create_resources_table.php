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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            // Student and Admin references
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete()->nullable();
            ;
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            ;

            // Resource details
            $table->enum('resource_type', ['pdf', 'video', 'article', 'workbook', 'media'])->default('pdf');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('resource_url')->nullable();

            // Timeline
            $table->dateTime('assigned_at');
            $table->date('deadline')->nullable();
            $table->dateTime('accessed_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Status and Progress
            $table->enum('status', ['pending', 'accessed', 'completed'])->default('pending');
            $table->integer('completion_percentage')->default(0);

            // Remarks
            $table->longText('admin_remarks')->nullable();
            $table->longText('overall_remarks')->nullable();
            $table->longText('student_response_to_remarks')->nullable();

            $table->timestamps();
        });

        // Create resource remarks table for per-resource feedback
        Schema::create('resource_remarks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();

            $table->longText('admin_remark')->nullable();
            $table->longText('student_response')->nullable();
            $table->dateTime('responded_at')->nullable();
            $table->string('file_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_remarks');
        Schema::dropIfExists('resources');
    }
};
