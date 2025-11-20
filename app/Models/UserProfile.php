<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'username',
        'phone_number',
        'sex',
        'age',
        'weight',
        'height',
        'emergency_contact',
        'medical_info',
        'relationship',
        'caregiver_id', // 1:1 relationship
        'profile_completed',
        'is_active',
        'last_login_at',
    ];

    protected $casts = [
        'emergency_contact' => 'array',
        'medical_info' => 'array',
        'profile_completed' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class, 'elderly_id');
    }

    public function medicationLogs(): HasMany
    {
        return $this->hasMany(MedicationLog::class, 'elderly_id');
    }

    public function healthMetrics(): HasMany
    {
        return $this->hasMany(HealthMetric::class, 'elderly_id');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class, 'elderly_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'elderly_id');
    }

    // 1:1 Relationship: Elderly belongs to ONE caregiver
    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'caregiver_id');
    }

    // 1:1 Relationship: Caregiver has ONE elderly user
    public function elderly(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'caregiver_id');
    }

    // Helper methods
    public function isElderly(): bool
    {
        return $this->user_type === 'elderly';
    }

    public function isCaregiver(): bool
    {
        return $this->user_type === 'caregiver';
    }
}
