<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected $fillable = [
        'elderly_id',
        'caregiver_id',
        'name',
        'dosage',
        'instructions',
        'days_of_week',
        'specific_dates',
        'times_of_day',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'specific_dates' => 'array',
        'times_of_day' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function elderly(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'elderly_id');
    }

    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'caregiver_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MedicationLog::class);
    }
}
