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
            $table->date('start_date')->nullable()->after('times_of_day');
            $table->date('end_date')->nullable()->after('start_date');
            $table->json('specific_dates')->nullable()->after('days_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'specific_dates']);
        });
    }
};
