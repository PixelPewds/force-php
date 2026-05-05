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
        Schema::create('schedule_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'pending'])->default('pending');
            $table->longText('notes')->nullable();
            $table->string('calendly_event_id')->nullable();
            $table->string('calendly_event_url')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_calls');
    }
};
