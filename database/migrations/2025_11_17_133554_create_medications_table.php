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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elderly_id')->constrained('user_profiles')->onDelete('cascade');
            $table->foreignId('caregiver_id')->constrained('user_profiles')->onDelete('cascade');
            
            $table->string('name');
            $table->string('dosage');
            $table->text('instructions')->nullable();
            
            // Scheduling
            $table->json('days_of_week')->nullable(); // ["Monday", "Wednesday", "Friday"]
            $table->json('specific_dates')->nullable(); // For one-time medications
            $table->json('times_of_day'); // ["09:00", "21:00"]
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
