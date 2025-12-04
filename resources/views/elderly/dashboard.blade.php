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

    <!-- DATA FETCHING: Get Upcoming Events safely -->
    @php
        $upcomingEvents = [];
        try {
            if(class_exists('App\Models\CalendarEvent')) {
                $upcomingEvents = \App\Models\CalendarEvent::where('user_id', Auth::id())
                    ->where('start_time', '>=', now())
                    ->orderBy('start_time', 'asc')
                    ->take(3)
                    ->get();
            }
        } catch (\Exception $e) {
            // Prevent crash if table/model issues exist
        }
    @endphp

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
                
                <!-- PROFILE LINK (Updated as requested) -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 pl-2 group hover:bg-gray-50 rounded-2xl py-1 px-2 transition-all cursor-pointer" title="Manage Profile">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-[#000080] font-[900] text-lg group-hover:bg-[#000080] group-hover:text-white transition-colors">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <!-- Small edit indicator -->
                        <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 border border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 leading-tight group-hover:text-[#000080] transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium">Patient</p>
                    </div>
                </a>

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
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-3xl font-[800] text-gray-900">Dashboard Overview</h2>
            <p class="text-gray-500">Here's your daily health summary.</p>
        </div>

        <!-- NEW: Calendar Widget (Replaces the old 2-card row) -->
        <div class="mb-8">
            <a href="{{ route('calendar.index') }}" class="group bg-white rounded-[24px] p-8 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all block relative overflow-hidden">
                <!-- Decorative BG -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-50 rounded-full -mr-20 -mt-20 opacity-50 group-hover:scale-110 transition-transform"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <!-- Left Side: Title & Description -->
                    <div class="max-w-md">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-[800] text-2xl text-gray-900 group-hover:text-purple-700 transition-colors">My Schedule</h3>
                        </div>
                        <p class="text-gray-500 font-medium">
                            View your appointments, medication reminders, and daily events. Click to manage your full calendar.
                        </p>
                    </div>

                    <!-- Right Side: Upcoming Events Preview -->
                    <div class="flex-grow w-full lg:w-auto">
                        @if(count($upcomingEvents) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach($upcomingEvents as $event)
                                    <div class="bg-purple-50/50 rounded-2xl p-4 border border-purple-100 group-hover:bg-purple-50 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-[900] text-purple-600 uppercase bg-white px-2 py-0.5 rounded-full shadow-sm">
                                                {{ \Carbon\Carbon::parse($event->start_time)->isToday() ? 'TODAY' : \Carbon\Carbon::parse($event->start_time)->format('M d') }}
                                            </span>
                                            <span class="text-xs font-bold text-gray-500">
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                        <p class="font-[800] text-gray-900 text-sm truncate">{{ $event->title }}</p>
                                        <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ $event->type ?? 'Event' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center w-full">
                                <p class="text-sm font-bold text-gray-500 mb-1">No upcoming events.</p>
                                <p class="text-xs text-gray-400">Tap here to schedule something new.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Arrow Icon (Desktop only) -->
                    <div class="hidden lg:flex bg-white border border-gray-200 w-12 h-12 rounded-full items-center justify-center text-gray-400 group-hover:bg-purple-600 group-hover:text-white group-hover:border-purple-600 transition-all flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>
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
                    <div class="flex items-center gap-3">
                        @if($googleFitConnected)
                            <button onclick="syncGoogleFit()" id="syncBtn" class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full border border-green-200 hover:bg-green-100 transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Sync Google Fit
                            </button>
                        @else
                            <a href="{{ route('elderly.googlefit.connect') }}" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-200 hover:bg-blue-100 transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                                Connect Google Fit
                            </a>
                        @endif
                        <span class="text-xs font-bold text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-200">
                            {{ $completedVitals }}/{{ $totalRequiredVitals }} recorded
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Vital Card: Blood Pressure -->
                    @php 
                        $bp = $vitalsData['blood_pressure'] ?? ['recorded' => false];
                        $bpStatus = null;
                        if ($bp['recorded'] && isset($bp['value_text'])) {
                            $parts = explode('/', $bp['value_text']);
                            if (count($parts) === 2) {
                                $sys = intval($parts[0]);
                                $dia = intval($parts[1]);
                                if ($sys >= 180 || $dia >= 120) {
                                    $bpStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                                } elseif ($sys >= 140 || $dia >= 90) {
                                    $bpStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                                } elseif ($sys >= 130 || $dia >= 80) {
                                    $bpStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                                } elseif ($sys < 90 || $dia < 60) {
                                    $bpStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                                } else {
                                    $bpStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                                }
                            }
                        }
                    @endphp
                    <a href="{{ route('elderly.vitals.blood_pressure') }}" class="vital-card bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-red-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group cursor-pointer" data-type="blood_pressure">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($bp['recorded'])
                                    @if(($bp['source'] ?? 'manual') === 'google_fit')
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Google Fit</span>
                                    @endif
                                    @if($bpStatus)
                                        <span class="text-[10px] font-bold {{ $bpStatus['bg'] }} {{ $bpStatus['text'] }} px-2 py-1 rounded-full">{{ $bpStatus['label'] }}</span>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Blood Pressure</h4>
                            @if($bp['recorded'])
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-[900] text-gray-900">{{ $bp['value_text'] }}</span>
                                    <span class="text-sm text-gray-400">mmHg</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $bp['measured_at']?->format('g:i A') }}</p>
                            @else
                                <div class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm group-hover:border-red-400 group-hover:text-red-500 transition-colors flex items-center justify-center gap-2">
                                    <span>+</span> Measure
                                </div>
                            @endif
                        </div>
                    </a>

                    <!-- Vital Card: Sugar Level -->
                    @php 
                        $sugar = $vitalsData['sugar_level'] ?? ['recorded' => false];
                        $sugarStatus = null;
                        if ($sugar['recorded'] && isset($sugar['value'])) {
                            $val = floatval($sugar['value']);
                            if ($val >= 250) {
                                $sugarStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                            } elseif ($val >= 180) {
                                $sugarStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                            } elseif ($val >= 126) {
                                $sugarStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                            } elseif ($val < 70) {
                                $sugarStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                            } else {
                                $sugarStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                            }
                        }
                    @endphp
                    <a href="{{ route('elderly.vitals.sugar_level') }}" class="vital-card bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-blue-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group cursor-pointer" data-type="sugar_level">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($sugar['recorded'])
                                    @if($sugarStatus)
                                        <span class="text-[10px] font-bold {{ $sugarStatus['bg'] }} {{ $sugarStatus['text'] }} px-2 py-1 rounded-full">{{ $sugarStatus['label'] }}</span>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Sugar Level</h4>
                            @if($sugar['recorded'])
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-[900] text-gray-900">{{ intval($sugar['value']) }}</span>
                                    <span class="text-sm text-gray-400">mg/dL</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $sugar['measured_at']?->format('g:i A') }}</p>
                            @else
                                <div class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm group-hover:border-blue-400 group-hover:text-blue-500 transition-colors flex items-center justify-center gap-2">
                                    <span>+</span> Measure
                                </div>
                            @endif
                        </div>
                    </a>

                    <!-- Vital Card: Temperature -->
                    @php 
                        $temp = $vitalsData['temperature'] ?? ['recorded' => false];
                        $tempStatus = null;
                        if ($temp['recorded'] && isset($temp['value'])) {
                            $val = floatval($temp['value']);
                            if ($val >= 39.5) {
                                $tempStatus = ['label' => 'High Fever', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                            } elseif ($val >= 38.0) {
                                $tempStatus = ['label' => 'Fever', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                            } elseif ($val >= 37.3) {
                                $tempStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                            } elseif ($val < 36.0) {
                                $tempStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                            } else {
                                $tempStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                            }
                        }
                    @endphp
                    <a href="{{ route('elderly.vitals.temperature') }}" class="vital-card bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-orange-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group cursor-pointer" data-type="temperature">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($temp['recorded'])
                                    @if(($temp['source'] ?? 'manual') === 'google_fit')
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Google Fit</span>
                                    @endif
                                    @if($tempStatus)
                                        <span class="text-[10px] font-bold {{ $tempStatus['bg'] }} {{ $tempStatus['text'] }} px-2 py-1 rounded-full">{{ $tempStatus['label'] }}</span>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Temperature</h4>
                            @if($temp['recorded'])
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-[900] text-gray-900">{{ number_format($temp['value'], 1) }}</span>
                                    <span class="text-sm text-gray-400">°C</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $temp['measured_at']?->format('g:i A') }}</p>
                            @else
                                <div class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm group-hover:border-orange-400 group-hover:text-orange-500 transition-colors flex items-center justify-center gap-2">
                                    <span>+</span> Measure
                                </div>
                            @endif
                        </div>
                    </a>

                    <!-- Vital Card: Heart Rate -->
                    @php 
                        $hr = $vitalsData['heart_rate'] ?? ['recorded' => false];
                        $hrStatus = null;
                        if ($hr['recorded'] && isset($hr['value'])) {
                            $val = floatval($hr['value']);
                            if ($val >= 150) {
                                $hrStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                            } elseif ($val >= 100) {
                                $hrStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                            } elseif ($val < 50) {
                                $hrStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                            } elseif ($val < 60) {
                                $hrStatus = ['label' => 'Slow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                            } else {
                                $hrStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                            }
                        }
                    @endphp
                    <a href="{{ route('elderly.vitals.heart_rate') }}" class="vital-card bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:border-rose-200 transition-all hover:shadow-md h-48 flex flex-col justify-between group cursor-pointer" data-type="heart_rate">
                        <div class="flex justify-between items-start">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($hr['recorded'])
                                    @if(($hr['source'] ?? 'manual') === 'google_fit')
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Google Fit</span>
                                    @endif
                                    @if($hrStatus)
                                        <span class="text-[10px] font-bold {{ $hrStatus['bg'] }} {{ $hrStatus['text'] }} px-2 py-1 rounded-full">{{ $hrStatus['label'] }}</span>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-rose-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-[700] text-gray-500 text-sm uppercase tracking-wide mb-1">Heart Rate</h4>
                            @if($hr['recorded'])
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-[900] text-gray-900">{{ intval($hr['value']) }}</span>
                                    <span class="text-sm text-gray-400">bpm</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $hr['measured_at']?->format('g:i A') }}</p>
                            @else
                                <div class="w-full py-3 mt-1 rounded-xl border-2 border-dashed border-gray-300 text-gray-400 font-bold text-sm group-hover:border-rose-400 group-hover:text-rose-500 transition-colors flex items-center justify-center gap-2">
                                    <span>+</span> Measure
                                </div>
                            @endif
                        </div>
                    </a>

                </div>

                <!-- Steps Progress Card -->
                <div class="mt-6 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[24px] p-6 shadow-lg shadow-emerald-900/20 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-2xl">👟</span>
                                    <h3 class="font-[800] text-lg">Today's Steps</h3>
                                </div>
                                <p class="text-white/70 text-xs">
                                    @if($googleFitConnected)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                                            Synced from Google Fit
                                        </span>
                                    @else
                                        Connect Google Fit to track steps
                                    @endif
                                </p>
                            </div>
                            @if($stepsData)
                                <div class="text-right">
                                    <div class="text-3xl font-[900]">{{ number_format($stepsData['value']) }}</div>
                                    <div class="text-white/70 text-xs">/ {{ number_format($stepsData['goal']) }} goal</div>
                                </div>
                            @else
                                <div class="text-right">
                                    <div class="text-3xl font-[900]">--</div>
                                    <div class="text-white/70 text-xs">No data yet</div>
                                </div>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        @php 
                            $stepsProgress = $stepsData ? min(100, round(($stepsData['value'] / $stepsData['goal']) * 100)) : 0;
                        @endphp
                        <div class="h-3 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $stepsProgress }}%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-3 text-sm">
                            <span class="text-white/80">{{ $stepsProgress }}% of daily goal</span>
                            @if($stepsData && $stepsData['value'] >= $stepsData['goal'])
                                <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-bold flex items-center gap-1">
                                    🎉 Goal Reached!
                                </span>
                            @elseif($stepsData)
                                <span class="text-white/60 text-xs">
                                    {{ number_format($stepsData['goal'] - $stepsData['value']) }} steps to go
                                </span>
                            @endif
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
        // VITALS RECORDING
        // ==========================================
        const vitalConfigs = {
            blood_pressure: {
                name: 'Blood Pressure',
                icon: '❤️',
                unit: 'mmHg',
                color: 'red',
                inputType: 'bp', // Special type for blood pressure
                hint: 'Enter systolic and diastolic values'
            },
            sugar_level: {
                name: 'Sugar Level',
                icon: '🩸',
                unit: 'mg/dL',
                color: 'blue',
                inputType: 'number',
                placeholder: '100',
                min: 50,
                max: 500,
                hint: 'Normal range: 70-100 mg/dL (fasting)'
            },
            temperature: {
                name: 'Temperature',
                icon: '🌡️',
                unit: '°C',
                color: 'orange',
                inputType: 'number',
                placeholder: '36.5',
                min: 35,
                max: 42,
                step: 0.1,
                hint: 'Normal range: 36.1-37.2°C'
            },
            heart_rate: {
                name: 'Heart Rate',
                icon: '💓',
                unit: 'bpm',
                color: 'rose',
                inputType: 'number',
                placeholder: '72',
                min: 40,
                max: 200,
                hint: 'Normal resting: 60-100 bpm'
            }
        };

        function openVitalModal(type) {
            const config = vitalConfigs[type];
            if (!config) return;

            // Build input HTML based on type
            let inputHtml = '';
            if (type === 'blood_pressure') {
                inputHtml = `
                    <div class="flex gap-3 items-center">
                        <div class="flex-1">
                            <input 
                                type="number" 
                                id="systolicValue"
                                name="systolic"
                                placeholder="120"
                                min="60"
                                max="250"
                                class="w-full px-4 py-4 text-2xl font-bold text-center border-2 border-gray-200 rounded-xl focus:border-${config.color}-400 focus:ring-4 focus:ring-${config.color}-100 transition-all outline-none"
                                required
                                autofocus
                            >
                            <p class="text-xs text-gray-400 mt-1 text-center">Systolic</p>
                        </div>
                        <span class="text-3xl text-gray-300 font-bold">/</span>
                        <div class="flex-1">
                            <input 
                                type="number" 
                                id="diastolicValue"
                                name="diastolic"
                                placeholder="80"
                                min="40"
                                max="150"
                                class="w-full px-4 py-4 text-2xl font-bold text-center border-2 border-gray-200 rounded-xl focus:border-${config.color}-400 focus:ring-4 focus:ring-${config.color}-100 transition-all outline-none"
                                required
                            >
                            <p class="text-xs text-gray-400 mt-1 text-center">Diastolic</p>
                        </div>
                        <span class="text-gray-400 font-bold self-start pt-4">${config.unit}</span>
                    </div>
                `;
            } else {
                inputHtml = `
                    <div class="relative">
                        <input 
                            type="${config.inputType}" 
                            id="vitalValue"
                            name="value"
                            placeholder="${config.placeholder}"
                            ${config.min ? `min="${config.min}"` : ''}
                            ${config.max ? `max="${config.max}"` : ''}
                            ${config.step ? `step="${config.step}"` : ''}
                            class="w-full px-4 py-4 text-2xl font-bold text-center border-2 border-gray-200 rounded-xl focus:border-${config.color}-400 focus:ring-4 focus:ring-${config.color}-100 transition-all outline-none"
                            required
                            autofocus
                        >
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">${config.unit}</span>
                    </div>
                `;
            }

            // Create modal
            const modal = document.createElement('div');
            modal.id = 'vitalModal';
            modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all duration-300 scale-95 opacity-0" id="vitalModalContent">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-${config.color}-50 rounded-2xl flex items-center justify-center text-3xl">
                            ${config.icon}
                        </div>
                        <div>
                            <h3 class="font-[800] text-xl text-gray-900">${config.name}</h3>
                            <p class="text-sm text-gray-500">Record your measurement</p>
                        </div>
                    </div>

                    <form id="vitalForm" onsubmit="submitVital(event, '${type}')">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Value <span class="text-gray-400">(${config.unit})</span>
                            </label>
                            ${inputHtml}
                            <p class="text-xs text-gray-400 mt-2">${config.hint}</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Notes (optional)</label>
                            <textarea 
                                id="vitalNotes"
                                name="notes"
                                placeholder="Any additional notes..."
                                rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-${config.color}-400 focus:ring-4 focus:ring-${config.color}-100 transition-all outline-none resize-none text-sm"
                            ></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="closeVitalModal()" class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="submitVitalBtn" class="flex-1 py-3 px-4 bg-${config.color}-500 text-white font-bold rounded-xl hover:bg-${config.color}-600 transition-colors flex items-center justify-center gap-2">
                                <span>Save</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Animate in
            setTimeout(() => {
                const content = document.getElementById('vitalModalContent');
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);

            // Close on backdrop click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeVitalModal();
            });

            // Close on Escape key
            document.addEventListener('keydown', handleModalEscape);
        }

        function handleModalEscape(e) {
            if (e.key === 'Escape') closeVitalModal();
        }

        function closeVitalModal() {
            const modal = document.getElementById('vitalModal');
            if (!modal) return;

            const content = document.getElementById('vitalModalContent');
            content.style.transform = 'scale(0.95)';
            content.style.opacity = '0';

            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
            }, 200);

            document.removeEventListener('keydown', handleModalEscape);
        }

        async function submitVital(event, type) {
            event.preventDefault();
            
            const config = vitalConfigs[type];
            const notesInput = document.getElementById('vitalNotes');
            const submitBtn = document.getElementById('submitVitalBtn');
            const notes = notesInput.value.trim();

            // Handle blood pressure separately with two fields
            if (type === 'blood_pressure') {
                const systolicInput = document.getElementById('systolicValue');
                const diastolicInput = document.getElementById('diastolicValue');
                const systolic = systolicInput?.value?.trim();
                const diastolic = diastolicInput?.value?.trim();

                if (!systolic || !diastolic) {
                    showToast('Please enter both systolic and diastolic values', 'error');
                    return;
                }

                // Disable button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="animate-spin">⏳</span> Saving...';

                try {
                    const payload = {
                        type: type,
                        value_text: `${systolic}/${diastolic}`,
                        notes: notes || null
                    };

                    const response = await fetch('/my-vitals', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to save');
                    }

                    closeVitalModal();
                    showToast(`✅ ${config.name} recorded!`, 'success');
                    setTimeout(() => window.location.reload(), 500);

                } catch (error) {
                    showToast(`❌ ${error.message}`, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Save</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                }
                return;
            }

            // Handle other vital types
            const valueInput = document.getElementById('vitalValue');
            const value = valueInput.value.trim();

            if (!value) {
                showToast('Please enter a value', 'error');
                return;
            }

            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin">⏳</span> Saving...';

            try {
                const payload = {
                    type: type,
                    value: parseFloat(value),
                    notes: notes || null
                };

                const response = await fetch('/my-vitals', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to save');
                }

                closeVitalModal();
                showToast(`✅ ${config.name} recorded!`, 'success');
                
                // Reload page to show updated vitals
                setTimeout(() => window.location.reload(), 500);

            } catch (error) {
                console.error('Error:', error);
                showToast(`❌ ${error.message}`, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Save</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            }
        }

        // ==========================================
        // GOOGLE FIT SYNC
        // ==========================================
        async function syncGoogleFit() {
            const syncBtn = document.getElementById('syncBtn');
            if (!syncBtn) return;

            const originalContent = syncBtn.innerHTML;
            syncBtn.disabled = true;
            syncBtn.innerHTML = '<span class="animate-spin">⏳</span> Syncing...';

            try {
                const response = await fetch('/google-fit/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Sync failed');
                }

                showToast('✅ Google Fit synced!', 'success');
                
                // Show what was synced
                if (data.synced) {
                    const syncedItems = Object.entries(data.synced)
                        .map(([key, val]) => `${key}: ${val}`)
                        .join(', ');
                    if (syncedItems) {
                        setTimeout(() => showToast(`📊 ${syncedItems}`, 'info'), 1000);
                    }
                }

                // Reload to show new data
                setTimeout(() => window.location.reload(), 1500);

            } catch (error) {
                console.error('Error:', error);
                showToast(`❌ ${error.message}`, 'error');
            } finally {
                syncBtn.disabled = false;
                syncBtn.innerHTML = originalContent;
            }
        }

        // ==========================================
        // INITIALIZE ON PAGE LOAD
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animations to cards
            const cards = document.querySelectorAll('.checklist-item, .medication-card, .vital-card');
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