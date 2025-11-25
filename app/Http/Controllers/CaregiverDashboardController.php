<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthMetric;

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
                'mood' => null,
                'vitals' => []
            ]);
        }

        // Fetch latest metrics
        $mood = $elderly->healthMetrics()->mood()->latest('measured_at')->first();
        
        $heartRate = $elderly->healthMetrics()->heartRate()->latest('measured_at')->first();
        $bloodPressure = $elderly->healthMetrics()->bloodPressure()->latest('measured_at')->first();
        $sugarLevel = $elderly->healthMetrics()->sugarLevel()->latest('measured_at')->first();
        $temperature = $elderly->healthMetrics()->temperature()->latest('measured_at')->first();

        $vitals = [
            'heart_rate' => $heartRate,
            'blood_pressure' => $bloodPressure,
            'sugar_level' => $sugarLevel,
            'temperature' => $temperature,
        ];

        return view('caregiver.dashboard', compact('elderly', 'mood', 'vitals'));
    }
}
