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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')
                ->constrained('form_submissions')
                ->cascadeOnDelete();
            $table->foreignId('question_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('answer')->nullable();
            $table->foreignId('option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
