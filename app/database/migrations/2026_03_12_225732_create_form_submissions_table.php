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
        Schema::create('form_submissions', function (Blueprint $table) {

            $table->id();
            $table->foreignId('form_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->enum('status',['draft','submitted'])
                ->default('draft');
            $table->integer('current_step')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['form_id','student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
