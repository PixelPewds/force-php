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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('type', [
                'text',
                'textarea',
                'radio',
                'checkbox',
                'number',
                'email',
                'url',
                'tel',
                'range'
            ]);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_other')->default(false);
            $table->integer('range_number')->default(5);
            $table->string('has_image')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
