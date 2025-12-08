<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            
            // Who is this for?
            $table->foreignId('elderly_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caregiver_id')->nullable()->constrained('users')->onDelete('set null');

            // Basic Info
            $table->string('name');
            $table->string('dosage')->nullable(); // e.g. "500"
            $table->string('dosage_unit')->nullable(); // e.g. "mg"
            $table->text('instructions')->nullable();
            
            // Scheduling
            $table->string('frequency')->nullable(); // e.g. "daily", "twice_daily"
            $table->json('times_of_day')->nullable(); // Store ["08:00", "20:00"]
            
            // Inventory Tracking (From your deleted file)
            $table->boolean('track_inventory')->default(false);
            $table->integer('current_stock')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
