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
        Schema::create('caregiver_elderly', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained('user_profiles')->onDelete('cascade');
            $table->foreignId('elderly_id')->constrained('user_profiles')->onDelete('cascade');
            
            $table->timestamp('assigned_at')->useCurrent();
            
            $table->unique(['caregiver_id', 'elderly_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caregiver_elderly');
    }
};
