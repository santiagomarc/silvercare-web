<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ElderlyDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $elderlyId = $user->profile?->id;

        // Get medications assigned to this elderly
        $medications = collect();
        $todayMedications = collect();
        
        if ($elderlyId) {
            $medications = Medication::where('elderly_id', $elderlyId)
                ->where('is_active', true)
                ->get();
            
            // Filter today's medications based on days_of_week
            $todayName = Carbon::now()->format('l'); // e.g., "Monday"
            $todayMedications = $medications->filter(function ($med) use ($todayName) {
                return empty($med->days_of_week) || in_array($todayName, $med->days_of_week);
            });
        }

        // Get checklists for today
        $checklists = collect();
        $todayChecklists = collect();
        
        if ($elderlyId) {
            $checklists = Checklist::where('elderly_id', $elderlyId)->get();
            
            $todayChecklists = Checklist::where('elderly_id', $elderlyId)
                ->whereDate('due_date', Carbon::today())
                ->orderBy('due_time')
                ->get();
        }

        // Calculate progress
        $completedChecklists = $todayChecklists->where('is_completed', true)->count();
        $totalChecklists = $todayChecklists->count();
        $checklistProgress = $totalChecklists > 0 ? round(($completedChecklists / $totalChecklists) * 100) : 0;

        return view('elderly.dashboard', compact(
            'medications',
            'todayMedications',
            'checklists',
            'todayChecklists',
            'completedChecklists',
            'totalChecklists',
            'checklistProgress'
        ));
    }

    public function medications()
    {
        $user = Auth::user();
        $elderlyId = $user->profile?->id;

        $medications = collect();
        if ($elderlyId) {
            $medications = Medication::where('elderly_id', $elderlyId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('elderly.medications', compact('medications'));
    }

    public function checklists()
    {
        $user = Auth::user();
        $elderlyId = $user->profile?->id;

        $checklists = collect();
        if ($elderlyId) {
            $checklists = Checklist::where('elderly_id', $elderlyId)
                ->whereDate('due_date', '>=', Carbon::today()->subDays(7))
                ->orderBy('due_date')
                ->orderBy('due_time')
                ->get();
        }

        // Group by date
        $groupedChecklists = $checklists->groupBy(function ($item) {
            return $item->due_date->format('Y-m-d');
        });

        return view('elderly.checklists', compact('checklists', 'groupedChecklists'));
    }

    public function toggleChecklist(Checklist $checklist)
    {
        $user = Auth::user();
        $elderlyId = $user->profile?->id;

        // Ensure the checklist belongs to this elderly
        if ($checklist->elderly_id !== $elderlyId) {
            abort(403);
        }

        $checklist->update([
            'is_completed' => !$checklist->is_completed,
            'completed_at' => !$checklist->is_completed ? now() : null,
        ]);

        // Return JSON for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $checklist->is_completed,
                'message' => $checklist->is_completed ? 'Task completed!' : 'Task marked as incomplete'
            ]);
        }

        return back()->with('success', $checklist->is_completed ? 'Task completed!' : 'Task marked as incomplete');
    }
}
