<?php

namespace App\Services;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Create a notification
     */
    public function createNotification(array $data): Notification
    {
        return Notification::create([
            'elderly_id' => $data['elderly_id'],
            'type' => $data['type'], // medication_reminder, medication_taken, medication_missed, health_alert, etc.
            'title' => $data['title'],
            'message' => $data['message'],
            'severity' => $data['severity'] ?? 'reminder', // positive, negative, reminder, warning
            'metadata' => $data['metadata'] ?? null, // JSON data
            'custom_id' => $data['custom_id'] ?? null, // For duplicate prevention
        ]);
    }

    /**
     * Get all notifications for elderly
     */
    public function getNotificationsForElderly(int $elderlyProfileId, int $limit = 50): Collection
    {
        return Notification::where('elderly_id', $elderlyProfileId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notifications by type
     */
    public function getNotificationsByType(int $elderlyProfileId, string $type): Collection
    {
        return Notification::where('elderly_id', $elderlyProfileId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get notifications by severity
     */
    public function getNotificationsBySeverity(int $elderlyProfileId, string $severity): Collection
    {
        return Notification::where('elderly_id', $elderlyProfileId)
            ->where('severity', $severity)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get today's notifications
     */
    public function getTodaysNotifications(int $elderlyProfileId): Collection
    {
        return Notification::where('elderly_id', $elderlyProfileId)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Delete notification
     */
    public function deleteNotification(int $notificationId): bool
    {
        $notification = Notification::findOrFail($notificationId);
        return $notification->delete();
    }

    /**
     * Delete all notifications for elderly
     */
    public function deleteAllNotifications(int $elderlyProfileId): int
    {
        return Notification::where('elderly_id', $elderlyProfileId)->delete();
    }

    /**
     * Create medication taken notification
     */
    public function createMedicationTakenNotification(int $elderlyProfileId, string $medicationName): Notification
    {
        return $this->createNotification([
            'elderly_id' => $elderlyProfileId,
            'type' => 'medication_taken',
            'title' => 'Medication Taken',
            'message' => "You've taken {$medicationName}",
            'severity' => 'positive',
        ]);
    }

    /**
     * Create medication missed notification
     */
    public function createMedicationMissedNotification(int $elderlyProfileId, string $medicationName): Notification
    {
        return $this->createNotification([
            'elderly_id' => $elderlyProfileId,
            'type' => 'medication_missed',
            'title' => 'Medication Missed',
            'message' => "You missed {$medicationName}",
            'severity' => 'warning',
        ]);
    }

    /**
     * Create health alert notification
     */
    public function createHealthAlertNotification(int $elderlyProfileId, string $message): Notification
    {
        return $this->createNotification([
            'elderly_id' => $elderlyProfileId,
            'type' => 'health_alert',
            'title' => 'Health Alert',
            'message' => $message,
            'severity' => 'warning',
        ]);
    }
}
