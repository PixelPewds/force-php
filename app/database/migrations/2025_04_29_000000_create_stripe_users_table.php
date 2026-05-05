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
        Schema::create('stripe_users', function (Blueprint $table) {
            $table->id();
            
            // Parent Information
            $table->string('parent_name');
            $table->string('relation');
            $table->string('education');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->text('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            
            // Student Information
            $table->string('student_name');
            $table->string('grade');
            $table->string('gender');
            $table->string('school');
            $table->string('board');
            
            // Additional Information
            $table->string('career_clarity');
            $table->text('goals');
            $table->text('comments')->nullable();
            $table->string('program');
            
            // Timestamp and Payment Information
            $table->timestamp('timestamp');
            $table->string('payment_status')->default('pending');
            $table->string('stripe_session_id')->nullable();
            $table->string('client_reference_id')->nullable();
            
            // System timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_users');
    }
};
