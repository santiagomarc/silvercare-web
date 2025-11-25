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
        Schema::table('medications', function (Blueprint $table) {
            $table->string('dosage_unit')->nullable();
            $table->string('frequency')->nullable(); // daily, weekly, etc.
            $table->boolean('track_inventory')->default(false);
            $table->integer('current_stock')->nullable();
            $table->integer('low_stock_threshold')->nullable();
        });

        Schema::table('checklists', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->time('due_time')->nullable();
            $table->string('frequency')->nullable();
            $table->boolean('is_recurring')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['dosage_unit', 'frequency', 'track_inventory', 'current_stock', 'low_stock_threshold']);
        });

        Schema::table('checklists', function (Blueprint $table) {
            $table->dropColumn(['description', 'due_time', 'frequency', 'is_recurring']);
        });
    }
};
