<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SilverCare</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        
        /* Smooth Slider Styling */
        input[type=range] {
            -webkit-appearance: none;
            background: transparent;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 36px;
            width: 36px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid currentColor;
            cursor: pointer;
            margin-top: -14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transition: transform 0.1s ease;
        }
        input[type=range]:active::-webkit-slider-thumb {
            transform: scale(1.2);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 999px;
        }
        input[type=range]:focus { outline: none; }
        
        /* Scrollbar hiding */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Dose button states */
        .dose-btn {
            transition: all 0.2s ease;
        }
        .dose-btn.taken {
            background: rgba(34, 197, 94, 0.4) !important;
            border-color: rgba(34, 197, 94, 0.6) !important;
        }
        .dose-btn.taken-late {
            background: rgba(251, 191, 36, 0.4) !important;
            border-color: rgba(251, 191, 36, 0.6) !important;
        }
        .dose-btn.missed {
            background: rgba(239, 68, 68, 0.3) !important;
            border-color: rgba(239, 68, 68, 0.4) !important;
        }
        .dose-btn.active {
            background: rgba(255, 255, 255, 0.3) !important;
            animation: pulse-glow 2s infinite;
        }
        .dose-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(255, 255, 255, 0); }
        }
    </style>
</head>
<body class="bg-[#EBEBEB] min-h-screen">

    <!-- NAV BAR -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#000080] rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                    <span class="text-white font-[900] text-lg">SC</span>
                </div>
                <h1 class="text-2xl font-[900] tracking-tight text-gray-900">SILVER<span class="text-[#000080]">CARE</span></h1>
            </div>
            
            <div class="flex items-center gap-6">
                <p class="hidden md:block text-sm font-bold text-gray-500 uppercase tracking-wider">
                    {{ now()->format('l, F j, Y') }}
                </p>
                <div class="h-8 w-[1px] bg-gray-200 hidden md:block"></div>
                <div class="flex items-center gap-3 pl-2">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-[#000080] font-[900] text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium">Patient</p>
                    </div>
                </div>
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- DASHBOARD CONTENT -->
    <main class="max-w-[1600px] mx-auto px-6 lg:px-12 py-10">
        
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-3xl font-[800] text-gray-900">Dashboard Overview</h2>
            <p class="text-gray-500">Here's your daily health summary.</p>
        </div>

        <!-- THE DASHBOARD GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- LEFT COLUMN (3/12): Mood & Progress -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- 1. MOOD TRACKER (First - Most Important) -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                    <h3 class="font-[800] text-lg text-gray-900 mb-1">Mood of the Day</h3>
                    <p class="text-xs text-gray-400 font-medium mb-6">How are you feeling right now?</p>

                    <div class="flex flex-col items-center">
                        <!-- Dynamic Emoji -->
                        <div id="moodEmoji" class="text-6xl mb-2 transition-transform duration-300">😐</div>
                        <p id="moodLabel" class="font-[800] text-lg mb-6 transition-colors duration-300 text-gray-600">Neutral</p>

                        <!-- Slider -->
                        <div class="w-full relative h-10 flex items-center">
                            <input 
                                type="range" 
                                id="moodSlider"
                                min="1" 
                                max="5" 
                                value="3"
                                class="w-full"
                                style="color: #6B7280;"
                            >
                        </div>

                        <!-- Auto-Save Indicator -->
                        <div class="h-6 mt-2 flex items-center justify-center">
                            <span id="moodSaved" class="text-xs font-bold text-green-600 opacity-0 transition-opacity duration-300">
                                ✓ Auto-saved
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. DAILY PROGRESS CARD -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -mr-8 -mt-8 z-0"></div>
                    <div class="relative z-10">
                        <h3 class="font-[800] text-lg text-gray-900 mb-4">Daily Goals</h3>
                        
                        <div class="flex flex-col items-center py-4">
                            <!-- Circular Progress -->
                            <div class="relative w-32 h-32">
                                <svg class="transform -rotate-90 w-full h-full">
                                    <circle cx="64" cy="64" r="56" stroke="#F3F4F6" stroke-width="10" fill="none" />
                                    <circle 
                                        id="dailyProgressCircle"
                                        cx="64" cy="64" r="56" 
                                        stroke="#10B981" 
                                        stroke-width="10" 
                                        fill="none" 
                                        stroke-dasharray="352" 
                                        stroke-dashoffset="{{ 352 - (352 * $dailyGoalsProgress / 100) }}"
                                        stroke-linecap="round"
                                        class="transition-all duration-500"
                                    />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span id="dailyProgressPercent" class="text-2xl font-[900] text-gray-900">{{ $dailyGoalsProgress }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <!-- Tasks Progress -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">📋</span>
                                    <span class="text-sm text-gray-600">Tasks</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $checklistProgress }}%"></div>
                                    </div>
                                    <span class="font-[800] text-xs text-gray-900">{{ $completedChecklists }}/{{ $totalChecklists }}</span>
                                </div>
                            </div>
                            
                            <!-- Medications Progress -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">💊</span>
                                    <span class="text-sm text-gray-600">Medications</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $medicationProgress }}%"></div>
                                    </div>
                                    <span class="font-[800] text-xs text-gray-900">{{ $takenMedicationDoses }}/{{ $totalMedicationDoses }}</span>
                                </div>
                            </div>
                            
                            <!-- Vitals Progress -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">❤️</span>
                                    <span class="text-sm text-gray-600">Vitals</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-rose-500 rounded-full" style="width: {{ $vitalsProgress }}%"></div>
                                    </div>
                                    <span class="font-[800] text-xs text-gray-900">{{ $completedVitals }}/{{ $totalRequiredVitals }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CENTER COLUMN (6/12): Vitals Grid -->
            <div class="lg:col-span-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-[800] text-xl text-gray-900">Health Vitals</h3>
                    <span class="text-xs font-bold text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-200">Updated: Today</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Vital Card: Blood Pressure (Placeholder - will connect to HealthMetric later) -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-red-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Blood Pressure</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Sugar Level -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-blue-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Sugar Level</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Temperature -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-orange-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Temperature</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                    <!-- Vital Card: Heart Rate -->
                    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-rose-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Heart Rate</h4>
                            <button class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm hover:border-[#000080] hover:text-[#000080] transition-colors flex items-center justify-center gap-2">
                                <span>+</span> Measure
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN (3/12): Tasks & Meds -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- MEDICATION LIST (GREEN) - With Dose Tracking -->
                <a href="{{ route('elderly.medications') }}" class="block bg-gradient-to-br from-green-500 to-green-600 rounded-[24px] p-6 shadow-lg shadow-green-900/20 text-white flex flex-col hover:shadow-xl hover:scale-[1.01] transition-all duration-300 cursor-pointer" style="min-height: 380px;">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-[800] text-lg">Today's Medications</h3>
                            <p class="text-white/70 text-xs font-medium">
                                @php
                                    $totalDoses = 0;
                                    foreach($todayMedications as $med) {
                                        $totalDoses += count($med->times_of_day ?? []);
                                    }
                                @endphp
                                {{ $totalDoses }} doses scheduled
                            </p>
                        </div>
                        <span class="text-xs font-bold text-white/90 flex items-center gap-1">
                            View All <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </div>
                    
                    <div class="overflow-y-auto no-scrollbar space-y-3 flex-1" id="medicationContainer" onclick="event.stopPropagation();">
                        @forelse($todayMedications as $medication)
                            <div class="medication-card bg-white rounded-xl p-4 shadow-sm" data-medication-id="{{ $medication->id }}">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">💊</div>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-[800] text-gray-900 text-sm truncate">{{ $medication->name }}</h4>
                                        <p class="text-gray-600 text-xs font-medium">{{ $medication->dosage }} {{ $medication->dosage_unit }}</p>
                                        @if($medication->instructions)
                                            <p class="text-gray-400 text-[10px] mt-1 truncate">📝 {{ Str::limit($medication->instructions, 40) }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Dose Time Buttons -->
                                @if(!empty($medication->times_of_day))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($medication->times_of_day as $time)
                                            @php
                                                $logKey = $medication->id . '_' . $time;
                                                $log = $medicationLogs->get($logKey);
                                                $isTaken = $log?->is_taken ?? false;
                                                $takenAt = $log?->taken_at;
                                                
                                                // Calculate time window status
                                                $now = now();
                                                $scheduledTime = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $time);
                                                $windowStart = $scheduledTime->copy()->subMinutes(60);
                                                $windowEnd = $scheduledTime->copy()->addMinutes(60);
                                                
                                                $isWithinWindow = $now->between($windowStart, $windowEnd);
                                                $isPastWindow = $now->gt($windowEnd);
                                                $isBeforeWindow = $now->lt($windowStart);
                                                
                                                // Determine status class
                                                $statusClass = 'upcoming';
                                                $statusIcon = '○';
                                                $statusText = 'Upcoming';
                                                
                                                if ($isTaken) {
                                                    $wasLate = $takenAt && $takenAt->gt($windowEnd);
                                                    $statusClass = $wasLate ? 'taken-late' : 'taken';
                                                    $statusIcon = '✓';
                                                    $statusText = $wasLate ? 'Late' : 'Taken';
                                                } elseif ($isPastWindow) {
                                                    $statusClass = 'missed';
                                                    $statusIcon = '!';
                                                    $statusText = 'Missed';
                                                } elseif ($isWithinWindow) {
                                                    $statusClass = 'active';
                                                    $statusIcon = '●';
                                                    $statusText = 'Take Now';
                                                }
                                                
                                                $canTake = $isWithinWindow || $isPastWindow;
                                            @endphp
                                            <button 
                                                class="dose-btn {{ $statusClass }} flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-[11px] font-bold hover:scale-105 active:scale-95 text-gray-700"
                                                data-medication-id="{{ $medication->id }}"
                                                data-time="{{ $time }}"
                                                data-taken="{{ $isTaken ? 'true' : 'false' }}"
                                                data-can-take="{{ $canTake ? 'true' : 'false' }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); toggleMedicationDose(this);"
                                                {{ !$canTake && !$isTaken ? 'disabled' : '' }}
                                            >
                                                <span class="status-icon">{{ $statusIcon }}</span>
                                                <span class="time-text">{{ \Carbon\Carbon::parse($time)->format('g:i A') }}</span>
                                                @if($isTaken || $isPastWindow)
                                                    <span class="status-label text-[9px] opacity-75">({{ $statusText }})</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12 flex flex-col items-center bg-white/20 rounded-xl">
                                <div class="text-5xl mb-3 opacity-50">🎉</div>
                                <p class="text-white/90 text-sm font-bold">No medications today!</p>
                                <p class="text-white/70 text-xs mt-1">Enjoy your day</p>
                            </div>
                        @endforelse
                    </div>
                </a>

                <!-- CHECKLIST WIDGET - Enhanced -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-green-50 rounded-full opacity-50"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-[800] text-lg text-gray-900">Today's Tasks</h3>
                                <p class="text-xs text-gray-400 font-medium">
                                    <span id="completedCount">{{ $completedChecklists }}</span>/{{ $totalChecklists }} completed
                                </p>
                            </div>
                            <a href="{{ route('elderly.checklists') }}" class="text-xs font-bold text-[#000080] hover:underline flex items-center gap-1">
                                See All
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>

                        <!-- Mini Progress Bar -->
                        <div class="h-2 bg-gray-100 rounded-full mb-4 overflow-hidden">
                            <div id="progressBar" class="h-full bg-gradient-to-r from-green-400 to-green-500 rounded-full transition-all duration-500" 
                                 style="width: {{ $checklistProgress }}%"></div>
                        </div>

                        <div class="space-y-2" id="checklistContainer">
                            @php
                                $categoryIcons = [
                                    'Health' => '❤️',
                                    'Exercise' => '🏃',
                                    'Nutrition' => '🍎',
                                    'Social' => '👥',
                                    'Hygiene' => '🧼',
                                    'Mental' => '🧠',
                                    'Medication' => '💊',
                                    'Medical' => '🏥',
                                    'Daily' => '☀️',
                                    'Home' => '🏠',
                                    'Other' => '📋',
                                ];
                            @endphp
                            @forelse($todayChecklists->take(5) as $checklist)
                                <div class="checklist-item flex items-start gap-3 p-3 rounded-xl border transition-all duration-300 {{ $checklist->is_completed ? 'bg-green-50/50 border-green-200 opacity-75' : 'bg-white border-gray-100 hover:border-green-200 hover:bg-green-50/30' }}" 
                                     data-id="{{ $checklist->id }}"
                                     data-completed="{{ $checklist->is_completed ? 'true' : 'false' }}">
                                    
                                    <!-- Animated Checkbox -->
                                    <button 
                                        onclick="toggleChecklist({{ $checklist->id }}, this)"
                                        class="checkbox-btn flex-shrink-0 w-7 h-7 rounded-lg border-2 {{ $checklist->is_completed ? 'bg-green-500 border-green-500' : 'bg-white border-gray-300 hover:border-green-400' }} flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 mt-0.5">
                                        <svg class="check-icon w-4 h-4 text-white transition-all duration-300 {{ $checklist->is_completed ? 'opacity-100 scale-100' : 'opacity-0 scale-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>

                                    <!-- Category Icon -->
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                        {{ $categoryIcons[$checklist->category] ?? '📋' }}
                                    </div>

                                    <!-- Task Content -->
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="task-text text-sm font-bold transition-all duration-300 {{ $checklist->is_completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                                {{ $checklist->task }}
                                            </p>
                                            <!-- Priority Badge -->
                                            @if($checklist->priority === 'high')
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold bg-red-100 text-red-600">🔴 High</span>
                                            @elseif($checklist->priority === 'medium')
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold bg-yellow-100 text-yellow-700">🟡 Medium</span>
                                            @elseif($checklist->priority === 'low')
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold bg-gray-100 text-gray-500">🟢 Low</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            <!-- Category -->
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-medium">{{ $checklist->category ?? 'Other' }}</span>
                                            
                                            <!-- Time -->
                                            @if($checklist->due_time)
                                                <span class="text-[10px] text-gray-500 font-medium flex items-center gap-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($checklist->due_time)->format('g:i A') }}
                                                </span>
                                            @endif
                                            
                                            <!-- Recurring indicator -->
                                            @if($checklist->is_recurring)
                                                <span class="text-[10px] text-blue-500 font-medium flex items-center gap-0.5">
                                                    🔄 {{ ucfirst($checklist->frequency ?? 'Recurring') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Description preview -->
                                        @if($checklist->description)
                                            <p class="text-[10px] text-gray-500 mt-1 truncate">📝 {{ Str::limit($checklist->description, 60) }}</p>
                                        @endif
                                        <!-- Notes preview -->
                                        @if($checklist->notes && !$checklist->description)
                                            <p class="text-[10px] text-gray-400 mt-1 truncate italic">💬 {{ Str::limit($checklist->notes, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div class="text-5xl mb-3">🎉</div>
                                    <p class="text-gray-600 text-sm font-bold">All caught up!</p>
                                    <p class="text-gray-400 text-xs mt-1">No tasks for today</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // ==========================================
        // CONFIGURATION
        // ==========================================
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        let completedCount = {{ $completedChecklists }};
        const totalCount = {{ $totalChecklists }};

        // ==========================================
        // MOOD TRACKER with Auto-Save
        // ==========================================
        const moodSlider = document.getElementById('moodSlider');
        const moodEmoji = document.getElementById('moodEmoji');
        const moodLabel = document.getElementById('moodLabel');
        const moodSaved = document.getElementById('moodSaved');

        const moods = [
            { emoji: '😢', label: 'Very Sad', color: '#EF4444' },
            { emoji: '☹️', label: 'Sad', color: '#F97316' },
            { emoji: '😐', label: 'Neutral', color: '#6B7280' },
            { emoji: '🙂', label: 'Happy', color: '#65A30D' },
            { emoji: '😄', label: 'Very Happy', color: '#16A34A' }
        ];

        let saveTimeout;

        moodSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            const mood = moods[value - 1];

            // Update UI with animation
            moodEmoji.style.transform = 'scale(0.8)';
            setTimeout(() => {
                moodEmoji.textContent = mood.emoji;
                moodLabel.textContent = mood.label;
                moodLabel.style.color = mood.color;
                moodEmoji.style.transform = 'scale(1)';
                moodSlider.style.color = mood.color;
            }, 100);

            // Auto-save after 1 second
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => saveMood(value), 1000);
        });

        function saveMood(value) {
            // Show saved indicator with animation
            moodSaved.style.opacity = '1';
            moodSaved.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                moodSaved.style.opacity = '0';
                moodSaved.style.transform = 'translateY(-10px)';
            }, 2000);

            // TODO: Send to backend when HealthMetric routes are ready
            console.log('Mood saved:', value);
        }

        // ==========================================
        // CHECKLIST TOGGLE - FIXED WITH PROPER HEADERS
        // ==========================================
        async function toggleChecklist(checklistId, buttonElement) {
            const item = buttonElement.closest('.checklist-item');
            const checkIcon = buttonElement.querySelector('.check-icon');
            const taskText = item.querySelector('.task-text');
            const isCurrentlyCompleted = item.dataset.completed === 'true';

            // Disable button during request
            buttonElement.disabled = true;
            buttonElement.style.transform = 'scale(0.9)';

            try {
                const response = await fetch(`/my-checklists/${checklistId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                buttonElement.style.transform = 'scale(1)';
                
                if (data.is_completed) {
                    // Mark as completed - but KEEP visible
                    item.dataset.completed = 'true';
                    item.classList.add('bg-green-50/50', 'border-green-200', 'opacity-75');
                    item.classList.remove('bg-white', 'border-gray-100');
                    buttonElement.classList.remove('bg-white', 'border-gray-300', 'hover:border-green-400');
                    buttonElement.classList.add('bg-green-500', 'border-green-500');
                    checkIcon.classList.remove('opacity-0', 'scale-0');
                    checkIcon.classList.add('opacity-100', 'scale-100');
                    taskText.classList.add('line-through', 'text-gray-400');
                    
                    completedCount++;
                    createConfetti(buttonElement);
                    showToast('✅ Task completed!', 'success');
                } else {
                    // Mark as incomplete - KEEP visible
                    item.dataset.completed = 'false';
                    item.classList.remove('bg-green-50/50', 'border-green-200', 'opacity-75');
                    item.classList.add('bg-white', 'border-gray-100');
                    buttonElement.classList.remove('bg-green-500', 'border-green-500');
                    buttonElement.classList.add('bg-white', 'border-gray-300', 'hover:border-green-400');
                    checkIcon.classList.remove('opacity-100', 'scale-100');
                    checkIcon.classList.add('opacity-0', 'scale-0');
                    taskText.classList.remove('line-through', 'text-gray-400');
                    
                    completedCount--;
                    showToast('Task marked incomplete', 'info');
                }

                updateProgress();
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Failed to update task', 'error');
            } finally {
                buttonElement.disabled = false;
                buttonElement.style.transform = 'scale(1)';
            }
        }

        // ==========================================
        // MEDICATION DOSE TOGGLE
        // ==========================================
        async function toggleMedicationDose(button) {
            const medicationId = button.dataset.medicationId;
            const time = button.dataset.time;
            const isTaken = button.dataset.taken === 'true';
            const canTake = button.dataset.canTake === 'true';

            if (!canTake && !isTaken) {
                showToast('⏰ Too early! Wait until the scheduled time window (1 hour before).', 'info');
                return;
            }

            button.disabled = true;
            button.style.transform = 'scale(0.9)';

            const endpoint = isTaken ? `/my-medications/${medicationId}/undo` : `/my-medications/${medicationId}/take`;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ time: time })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to update');
                }

                const data = await response.json();
                
                button.style.transform = 'scale(1)';

                if (data.is_taken) {
                    // Mark as taken
                    button.dataset.taken = 'true';
                    button.classList.remove('upcoming', 'missed', 'active');
                    button.classList.add(data.taken_late ? 'taken-late' : 'taken');
                    button.querySelector('.status-icon').textContent = '✓';
                    
                    // Update or add status label
                    let statusLabel = button.querySelector('.status-label');
                    if (!statusLabel) {
                        statusLabel = document.createElement('span');
                        statusLabel.className = 'status-label text-[9px] opacity-75';
                        button.appendChild(statusLabel);
                    }
                    statusLabel.textContent = `(${data.taken_late ? 'Late' : 'Taken'})`;
                    
                    // Update medication progress in Daily Goals
                    updateMedicationProgress(1);
                    
                    createConfetti(button);
                    showToast(data.message, 'success');
                } else {
                    // Mark as not taken (undo)
                    button.dataset.taken = 'false';
                    button.classList.remove('taken', 'taken-late');
                    
                    // Re-evaluate current status
                    const now = new Date();
                    const [hours, minutes] = time.split(':').map(Number);
                    const scheduledTime = new Date();
                    scheduledTime.setHours(hours, minutes, 0, 0);
                    const windowEnd = new Date(scheduledTime.getTime() + 60 * 60 * 1000);
                    
                    if (now > windowEnd) {
                        button.classList.add('missed');
                        button.querySelector('.status-icon').textContent = '!';
                        let statusLabel = button.querySelector('.status-label');
                        if (statusLabel) statusLabel.textContent = '(Missed)';
                    } else {
                        button.classList.add('active');
                        button.querySelector('.status-icon').textContent = '●';
                        let statusLabel = button.querySelector('.status-label');
                        if (statusLabel) statusLabel.remove();
                    }
                    
                    // Update medication progress in Daily Goals
                    updateMedicationProgress(-1);
                    
                    showToast(data.message, 'info');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(`❌ ${error.message}`, 'error');
            } finally {
                button.disabled = false;
                button.style.transform = 'scale(1)';
            }
        }

        // ==========================================
        // PROGRESS UPDATE (Combined: Checklists, Medications, Vitals)
        // ==========================================
        // Track current values for real-time updates
        let takenMedicationDoses = {{ $takenMedicationDoses }};
        const totalMedicationDoses = {{ $totalMedicationDoses }};
        const completedVitals = {{ $completedVitals }};
        const totalRequiredVitals = {{ $totalRequiredVitals }};

        function updateProgress() {
            const progressBar = document.getElementById('progressBar');
            const completedCountEl = document.getElementById('completedCount');
            
            // Checklist progress
            const checklistProgress = totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : 0;
            
            // Medication progress
            const medicationProgress = totalMedicationDoses > 0 ? Math.round((takenMedicationDoses / totalMedicationDoses) * 100) : 0;
            
            // Vitals progress (stays constant - updated only on page reload when vitals are recorded)
            const vitalsProgress = totalRequiredVitals > 0 ? Math.round((completedVitals / totalRequiredVitals) * 100) : 0;
            
            // Calculate combined daily goals progress (weighted)
            let totalWeight = 0;
            let weightedProgress = 0;
            
            if (totalCount > 0) {
                totalWeight += 40;
                weightedProgress += checklistProgress * 40;
            }
            if (totalMedicationDoses > 0) {
                totalWeight += 40;
                weightedProgress += medicationProgress * 40;
            }
            if (totalRequiredVitals > 0) {
                totalWeight += 20;
                weightedProgress += vitalsProgress * 20;
            }
            
            const dailyGoalsProgress = totalWeight > 0 ? Math.round(weightedProgress / totalWeight) : 0;
            
            // Update mini progress bar in checklist widget
            if (progressBar) progressBar.style.width = `${checklistProgress}%`;
            if (completedCountEl) completedCountEl.textContent = completedCount;

            // Update the circular progress in Daily Goals
            const circleProgress = document.getElementById('dailyProgressCircle');
            if (circleProgress) {
                const dashOffset = 352 - (352 * dailyGoalsProgress / 100);
                circleProgress.style.strokeDashoffset = dashOffset;
            }

            // Update percentage text
            const percentageText = document.getElementById('dailyProgressPercent');
            if (percentageText) {
                percentageText.textContent = `${dailyGoalsProgress}%`;
            }
        }

        function updateMedicationProgress(delta) {
            takenMedicationDoses += delta;
            updateProgress();
        }

        // ==========================================
        // CONFETTI CELEBRATION EFFECT
        // ==========================================
        function createConfetti(element) {
            const colors = ['#10B981', '#34D399', '#6EE7B7', '#A7F3D0', '#FBBF24', '#F59E0B'];
            const rect = element.getBoundingClientRect();
            
            for (let i = 0; i < 15; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: 8px;
                    height: 8px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
                    pointer-events: none;
                    z-index: 9999;
                    left: ${rect.left + rect.width / 2}px;
                    top: ${rect.top + rect.height / 2}px;
                `;
                document.body.appendChild(confetti);
                
                const angle = (Math.random() * 360) * (Math.PI / 180);
                const velocity = 3 + Math.random() * 4;
                const vx = Math.cos(angle) * velocity;
                const vy = Math.sin(angle) * velocity;
                
                let x = 0, y = 0, opacity = 1;
                
                function animateConfetti() {
                    x += vx;
                    y += vy + 1; // gravity
                    opacity -= 0.02;
                    
                    confetti.style.transform = `translate(${x}px, ${y}px) rotate(${x * 5}deg)`;
                    confetti.style.opacity = opacity;
                    
                    if (opacity > 0) {
                        requestAnimationFrame(animateConfetti);
                    } else {
                        confetti.remove();
                    }
                }
                
                requestAnimationFrame(animateConfetti);
            }
        }

        // ==========================================
        // TOAST NOTIFICATIONS
        // ==========================================
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            toast.className = `fixed bottom-6 right-6 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg font-bold text-sm z-50 transform translate-y-20 opacity-0 transition-all duration-300`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            }, 10);
            
            // Animate out
            setTimeout(() => {
                toast.style.transform = 'translateY(20px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }

        // ==========================================
        // INITIALIZE ON PAGE LOAD
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animations to cards
            const cards = document.querySelectorAll('.checklist-item, .medication-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>

</body>
</html>