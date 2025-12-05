<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthMetric;
use App\Models\MedicationLog;
use App\Models\Checklist;
use Carbon\Carbon;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        $caregiver = Auth::user()->profile;
        
        // Ensure the user has a profile
        if (!$caregiver) {
            return redirect()->route('profile.complete');
        }

        $elderly = $caregiver->elderly;

        if (!$elderly) {
            return view('caregiver.dashboard', [
                'elderly' => null,
                'elderlyUser' => null,
                'mood' => null,
                'vitals' => [],
                'recentActivity' => collect(),
                'stats' => [],
            ]);
        }

        // Get the elderly user
        $elderlyUser = $elderly->user;

        // Fetch TODAY's latest metrics only (like Flutter version)
        $today = Carbon::today();
        
        $mood = HealthMetric::where('elderly_id', $elderly->id)
            ->where('type', 'mood')
            ->whereDate('measured_at', $today)
            ->latest('measured_at')
            ->first();
        
        $heartRate = HealthMetric::where('elderly_id', $elderly->id)
            ->where('type', 'heart_rate')
            ->whereDate('measured_at', $today)
            ->latest('measured_at')
            ->first();
            
        $bloodPressure = HealthMetric::where('elderly_id', $elderly->id)
            ->where('type', 'blood_pressure')
            ->whereDate('measured_at', $today)
            ->latest('measured_at')
            ->first();
            
        $sugarLevel = HealthMetric::where('elderly_id', $elderly->id)
            ->where('type', 'sugar_level')
            ->whereDate('measured_at', $today)
            ->latest('measured_at')
            ->first();
            
        $temperature = HealthMetric::where('elderly_id', $elderly->id)
            ->where('type', 'temperature')
            ->whereDate('measured_at', $today)
            ->latest('measured_at')
            ->first();

        $vitals = [
            'heart_rate' => $heartRate ? [
                'metric' => $heartRate,
                'status' => $this->getHeartRateStatus($heartRate->value),
            ] : null,
            'blood_pressure' => $bloodPressure ? [
                'metric' => $bloodPressure,
                'status' => $this->getBloodPressureStatus($bloodPressure->value_text),
            ] : null,
            'sugar_level' => $sugarLevel ? [
                'metric' => $sugarLevel,
                'status' => $this->getSugarLevelStatus($sugarLevel->value),
            ] : null,
            'temperature' => $temperature ? [
                'metric' => $temperature,
                'status' => $this->getTemperatureStatus($temperature->value),
            ] : null,
        ];

        // Get recent activity (last 7 days)
        $recentActivity = $this->getRecentActivity($elderly->id);

        // Get summary stats
        $stats = $this->getStats($elderly);

        return view('caregiver.dashboard', compact('elderly', 'elderlyUser', 'mood', 'vitals', 'recentActivity', 'stats'));
    }

    /**
     * Get recent activity for the elderly
     */
    private function getRecentActivity($elderlyId)
    {
        $activities = collect();
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Get medication logs (taken doses)
        $medicationLogs = MedicationLog::with('medication')
            ->where('elderly_id', $elderlyId)
            ->where('is_taken', true)
            ->where('taken_at', '>=', $sevenDaysAgo)
            ->orderBy('taken_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($medicationLogs as $log) {
            $activities->push([
                'type' => 'medication_taken',
                'title' => ($log->medication->name ?? 'Medication') . ' taken',
                'subtitle' => $log->scheduled_time ? Carbon::parse($log->scheduled_time)->format('g:i A') . ' dose' : 'Dose taken',
                'timestamp' => $log->taken_at,
                'icon' => '💊',
                'color' => 'green',
            ]);
        }

        // Get completed checklists
        $completedTasks = Checklist::where('elderly_id', $elderlyId)
            ->where('is_completed', true)
            ->where('completed_at', '>=', $sevenDaysAgo)
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($completedTasks as $task) {
            $activities->push([
                'type' => 'task_completed',
                'title' => $task->task . ' completed',
                'subtitle' => ucfirst($task->category ?? 'Task'),
                'timestamp' => $task->completed_at,
                'icon' => '✅',
                'color' => 'green',
            ]);
        }

        // Get recent vitals recordings
        $recentVitals = HealthMetric::where('elderly_id', $elderlyId)
            ->whereIn('type', ['heart_rate', 'blood_pressure', 'sugar_level', 'temperature'])
            ->where('measured_at', '>=', $sevenDaysAgo)
            ->orderBy('measured_at', 'desc')
            ->limit(10)
            ->get();

        $vitalNames = [
            'heart_rate' => 'Heart Rate',
            'blood_pressure' => 'Blood Pressure',
            'sugar_level' => 'Sugar Level',
            'temperature' => 'Temperature',
        ];

        $vitalIcons = [
            'heart_rate' => '❤️',
            'blood_pressure' => '🩺',
            'sugar_level' => '🍬',
            'temperature' => '🌡️',
        ];

        foreach ($recentVitals as $vital) {
            $value = $vital->type === 'blood_pressure' 
                ? $vital->value_text 
                : ($vital->type === 'temperature' ? number_format($vital->value, 1) . '°C' : intval($vital->value));
            
            $activities->push([
                'type' => 'vital_recorded',
                'title' => ($vitalNames[$vital->type] ?? 'Vital') . ' recorded',
                'subtitle' => $value . ($vital->source === 'google_fit' ? ' • Google Fit' : ''),
                'timestamp' => $vital->measured_at,
                'icon' => $vitalIcons[$vital->type] ?? '📊',
                'color' => 'blue',
            ]);
        }

        // Sort by timestamp and take the most recent 10
        return $activities->sortByDesc('timestamp')->take(10)->values();
    }

    /**
     * Get summary stats for the elderly
     */
    private function getStats($elderly)
    {
        $today = Carbon::today();

        // Today's medication adherence
        $todaysMeds = $elderly->trackedMedications()
            ->where('is_active', true)
            ->get();
        
        $totalDosesToday = 0;
        $takenDosesToday = 0;
        $dayOfWeek = $today->format('l');
        
        foreach ($todaysMeds as $med) {
            if (in_array($dayOfWeek, $med->days_of_week ?? [])) {
                $doseCount = count($med->times_of_day ?? []);
                $totalDosesToday += $doseCount;
                
                $takenToday = MedicationLog::where('medication_id', $med->id)
                    ->whereDate('scheduled_time', $today)
                    ->where('is_taken', true)
                    ->count();
                $takenDosesToday += $takenToday;
            }
        }
        
        $medicationAdherence = $totalDosesToday > 0 
            ? round(($takenDosesToday / $totalDosesToday) * 100) 
            : null;

        // Today's task completion
        $todaysTasks = Checklist::where('elderly_id', $elderly->id)
            ->whereDate('due_date', $today)
            ->get();
        
        $totalTasks = $todaysTasks->count();
        $completedTasks = $todaysTasks->where('is_completed', true)->count();
        
        $taskCompletion = $totalTasks > 0 
            ? round(($completedTasks / $totalTasks) * 100) 
            : null;

        // Vitals recorded today
        $vitalsToday = HealthMetric::where('elderly_id', $elderly->id)
            ->whereIn('type', ['heart_rate', 'blood_pressure', 'sugar_level', 'temperature'])
            ->whereDate('measured_at', $today)
            ->distinct('type')
            ->count('type');

        return [
            'medication_adherence' => $medicationAdherence,
            'doses_taken' => $takenDosesToday,
            'doses_total' => $totalDosesToday,
            'task_completion' => $taskCompletion,
            'tasks_completed' => $completedTasks,
            'tasks_total' => $totalTasks,
            'vitals_recorded' => $vitalsToday,
            'vitals_total' => 4,
        ];
    }

    /**
     * Health status helper methods (matching Flutter thresholds)
     */
    private function getHeartRateStatus($value)
    {
        if ($value >= 150) return ['label' => 'Critical', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'];
        if ($value >= 100) return ['label' => 'High', 'color' => 'orange', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
        if ($value < 50) return ['label' => 'Very Low', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'];
        if ($value < 60) return ['label' => 'Low', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
        return ['label' => 'Normal', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
    }

    private function getBloodPressureStatus($value)
    {
        if (!$value || !str_contains($value, '/')) {
            return ['label' => 'Unknown', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
        }
        
        $parts = explode('/', $value);
        $systolic = (int)$parts[0];
        $diastolic = (int)($parts[1] ?? 0);

        if ($systolic >= 180 || $diastolic >= 120) return ['label' => 'Critical', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'];
        if ($systolic >= 140 || $diastolic >= 90) return ['label' => 'High', 'color' => 'orange', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
        if ($systolic >= 130 || $diastolic >= 80) return ['label' => 'Elevated', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
        if ($systolic < 90 || $diastolic < 60) return ['label' => 'Low', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
        return ['label' => 'Normal', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
    }

    private function getSugarLevelStatus($value)
    {
        if ($value >= 250) return ['label' => 'Critical', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'];
        if ($value >= 180) return ['label' => 'High', 'color' => 'orange', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
        if ($value >= 126) return ['label' => 'Elevated', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
        if ($value < 70) return ['label' => 'Low', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
        return ['label' => 'Normal', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
    }

    private function getTemperatureStatus($value)
    {
        if ($value >= 39.5) return ['label' => 'High Fever', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'];
        if ($value >= 38.0) return ['label' => 'Fever', 'color' => 'orange', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
        if ($value >= 37.3) return ['label' => 'Elevated', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
        if ($value < 36.0) return ['label' => 'Low', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
        return ['label' => 'Normal', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
    }
}
