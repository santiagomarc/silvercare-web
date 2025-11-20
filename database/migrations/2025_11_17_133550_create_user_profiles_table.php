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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('user_type', ['elderly', 'caregiver'])->index();
            
            // Elderly-specific fields
            $table->string('username')->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('sex', ['Male', 'Female', 'Other'])->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            
            // Emergency Contact (JSON)
            $table->json('emergency_contact')->nullable();
            
            // Medical Info (JSON)
            $table->json('medical_info')->nullable();
            
            // Caregiver-specific fields
            $table->string('relationship')->nullable(); // For caregivers
            
            // Common fields
            $table->boolean('profile_completed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
