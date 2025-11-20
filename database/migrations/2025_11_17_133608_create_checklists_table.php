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
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elderly_id')->constrained('user_profiles')->onDelete('cascade');
            $table->foreignId('caregiver_id')->constrained('user_profiles')->onDelete('cascade');
            
            $table->string('task');
            $table->string('category')->default('General'); // Morning, Health, General, etc.
            $table->date('due_date');
            
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['elderly_id', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
