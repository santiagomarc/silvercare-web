<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileCompletionController extends Controller
{
    /**
     * Display the profile completion form (3-step wizard).
     */
    public function show(): View
    {
        $user = Auth::user();
        $profile = $user->profile;

        // If already completed, redirect to dashboard
        if ($profile && $profile->profile_completed) {
            return $this->redirectToDashboard($profile->user_type);
        }

        return view('auth.profile-completion', compact('profile'));
    }

    /**
     * Handle profile completion submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Validate the complete profile data
        $validated = $request->validate([
            // Step 1: Personal Details
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'weight' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'height' => ['nullable', 'numeric', 'min:1', 'max:300'],
            
            // Step 2: Emergency Contact
            'emergency_name' => ['nullable', 'string', 'max:255'],
            'emergency_phone' => ['nullable', 'string', 'max:20'],
            'emergency_relationship' => ['nullable', 'string', 'max:255'],
            
            // Step 3: Medical Info
            'conditions' => ['nullable', 'string'],
            'medications' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
        ]);

        // Prepare emergency contact data
        $emergencyContact = null;
        if ($validated['emergency_name'] && $validated['emergency_phone']) {
            $emergencyContact = [
                'name' => $validated['emergency_name'],
                'phone' => $validated['emergency_phone'],
                'relationship' => $validated['emergency_relationship'] ?? '',
            ];
        }

        // Prepare medical info data
        $medicalInfo = [
            'conditions' => $validated['conditions'] ? array_filter(array_map('trim', explode(',', $validated['conditions']))) : [],
            'medications' => $validated['medications'] ? array_filter(array_map('trim', explode(',', $validated['medications']))) : [],
            'allergies' => $validated['allergies'] ? array_filter(array_map('trim', explode(',', $validated['allergies']))) : [],
        ];

        // Update profile
        $profile->update([
            'age' => $validated['age'],
            'weight' => $validated['weight'],
            'height' => $validated['height'],
            'emergency_contact' => $emergencyContact,
            'medical_info' => $medicalInfo,
            'profile_completed' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile completed successfully!');
    }

    /**
     * Skip profile completion (mark as completed but without data).
     */
    public function skip(): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;

        $profile->update([
            'profile_completed' => true,
        ]);

        return redirect()->route('dashboard')->with('info', 'Profile completion skipped. You can complete it later from your settings.');
    }

    /**
     * Redirect to appropriate dashboard based on user type.
     */
    protected function redirectToDashboard(string $userType): RedirectResponse
    {
        if ($userType === 'elderly') {
            return redirect()->route('dashboard');
        } elseif ($userType === 'caregiver') {
            return redirect()->route('caregiver.dashboard');
        }
        
        return redirect()->route('dashboard');
    }
}
