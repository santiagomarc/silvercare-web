<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['name'] }} - SilverCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.3s ease forwards; }
        
        .record-card:nth-child(1) { animation-delay: 0.05s; }
        .record-card:nth-child(2) { animation-delay: 0.1s; }
        .record-card:nth-child(3) { animation-delay: 0.15s; }
        .record-card:nth-child(4) { animation-delay: 0.2s; }
        .record-card:nth-child(5) { animation-delay: 0.25s; }
    </style>
</head>
<body class="bg-[#C0C0C0] min-h-screen">

    <!-- HEADER -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-bold">Back</span>
                </a>
            </div>
            <h1 class="text-xl font-[800] text-gray-900 flex items-center gap-2">
                <span class="text-2xl">{{ $config['icon'] }}</span>
                {{ $config['name'] }}
            </h1>
            <div class="w-20"></div> <!-- Spacer for centering -->
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        
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

        <!-- Google Fit Card (if supported) -->
        @if($supportsGoogleFit)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-green-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-[700] text-gray-900">Google Fit</h3>
                        <p class="text-sm text-gray-500">
                            @if($googleFitConnected)
                                <span class="text-green-600">✓ Connected</span> - Auto-syncing enabled
                            @else
                                Connect to automatically sync your {{ strtolower($config['name']) }} data
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($googleFitConnected)
                        <button onclick="syncGoogleFit()" id="syncBtn" class="px-4 py-2 bg-blue-50 text-blue-600 font-bold text-sm rounded-xl hover:bg-blue-100 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Sync Now
                        </button>
                        <form action="{{ route('elderly.googlefit.disconnect') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-gray-400 hover:text-red-500 text-sm font-medium transition-colors">
                                Disconnect
                            </button>
                        </form>
                    @else
                        <a href="{{ route('elderly.googlefit.connect') }}" class="px-4 py-2 bg-blue-500 text-white font-bold text-sm rounded-xl hover:bg-blue-600 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            Connect
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Auto-sync indicator -->
            @if($googleFitConnected)
            <div id="autoSyncStatus" class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-500 flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span>Checking for new data...</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Stats Summary -->
        @if($stats['count'] > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Latest</p>
                <p class="text-xl font-[800] text-gray-900">
                    @if($type === 'blood_pressure')
                        {{ $stats['latest']->value_text ?? '-' }}
                    @else
                        {{ $type === 'temperature' ? number_format($stats['latest']->value, 1) : intval($stats['latest']->value) }}
                    @endif
                </p>
                <p class="text-xs text-gray-400">{{ $config['unit'] }}</p>
            </div>
            @if($type !== 'blood_pressure')
            <div class="bg-white rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Average</p>
                <p class="text-xl font-[800] text-gray-900">{{ $stats['avg'] ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $config['unit'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Min</p>
                <p class="text-xl font-[800] text-green-600">{{ $stats['min'] ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $config['unit'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Max</p>
                <p class="text-xl font-[800] text-red-600">{{ $stats['max'] ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $config['unit'] }}</p>
            </div>
            @else
            <div class="bg-white rounded-xl p-4 text-center col-span-2">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Records</p>
                <p class="text-xl font-[800] text-gray-900">{{ $stats['count'] }}</p>
                <p class="text-xs text-gray-400">Last 30 days</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Add New Record Button -->
        <button onclick="openRecordModal()" class="w-full bg-gradient-to-r from-{{ $config['color'] }}-500 to-{{ $config['color'] }}-600 text-white font-bold py-4 rounded-xl mb-6 hover:shadow-lg transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Record
        </button>

        <!-- History Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-[800] text-lg text-gray-900">History</h2>
                <p class="text-sm text-gray-500">Last 30 days</p>
            </div>
            
            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto no-scrollbar">
                @forelse($metrics as $metric)
                @php
                    // Determine health status for this record
                    $recordStatus = null;
                    if ($type === 'blood_pressure' && $metric->value_text) {
                        $parts = explode('/', $metric->value_text);
                        if (count($parts) === 2) {
                            $sys = intval($parts[0]);
                            $dia = intval($parts[1]);
                            if ($sys >= 180 || $dia >= 120) {
                                $recordStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                            } elseif ($sys >= 140 || $dia >= 90) {
                                $recordStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                            } elseif ($sys >= 130 || $dia >= 80) {
                                $recordStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                            } elseif ($sys < 90 || $dia < 60) {
                                $recordStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                            } else {
                                $recordStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                            }
                        }
                    } elseif ($type === 'sugar_level' && $metric->value) {
                        $val = floatval($metric->value);
                        if ($val >= 250) {
                            $recordStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                        } elseif ($val >= 180) {
                            $recordStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                        } elseif ($val >= 126) {
                            $recordStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                        } elseif ($val < 70) {
                            $recordStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                        } else {
                            $recordStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                        }
                    } elseif ($type === 'temperature' && $metric->value) {
                        $val = floatval($metric->value);
                        if ($val >= 39.5) {
                            $recordStatus = ['label' => 'High Fever', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                        } elseif ($val >= 38.0) {
                            $recordStatus = ['label' => 'Fever', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                        } elseif ($val >= 37.3) {
                            $recordStatus = ['label' => 'Elevated', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                        } elseif ($val < 36.0) {
                            $recordStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                        } else {
                            $recordStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                        }
                    } elseif ($type === 'heart_rate' && $metric->value) {
                        $val = floatval($metric->value);
                        if ($val >= 150) {
                            $recordStatus = ['label' => 'Critical', 'bg' => 'bg-red-500', 'text' => 'text-white'];
                        } elseif ($val >= 100) {
                            $recordStatus = ['label' => 'High', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'];
                        } elseif ($val < 50) {
                            $recordStatus = ['label' => 'Low', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'];
                        } elseif ($val < 60) {
                            $recordStatus = ['label' => 'Slow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'];
                        } else {
                            $recordStatus = ['label' => 'Normal', 'bg' => 'bg-green-100', 'text' => 'text-green-700'];
                        }
                    }
                @endphp
                <div class="record-card p-4 hover:bg-gray-50 transition-colors opacity-0 fade-in flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-{{ $config['color'] }}-50 rounded-xl flex items-center justify-center text-2xl">
                            {{ $config['icon'] }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-[800] text-gray-900">
                                    @if($type === 'blood_pressure')
                                        {{ $metric->value_text }}
                                    @elseif($type === 'temperature')
                                        {{ number_format($metric->value, 1) }}
                                    @else
                                        {{ intval($metric->value) }}
                                    @endif
                                </span>
                                <span class="text-sm text-gray-400">{{ $config['unit'] }}</span>
                                @if($recordStatus)
                                    <span class="text-[10px] font-bold {{ $recordStatus['bg'] }} {{ $recordStatus['text'] }} px-2 py-0.5 rounded-full">{{ $recordStatus['label'] }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $metric->measured_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Source Badge -->
                        @if($metric->source === 'google_fit')
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                                Google Fit
                            </span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                                Manual
                            </span>
                        @endif
                        
                        <!-- Delete Button -->
                        <button onclick="deleteRecord({{ $metric->id }})" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">{{ $config['icon'] }}</div>
                    <h3 class="font-bold text-gray-700 mb-2">No Records Yet</h3>
                    <p class="text-sm text-gray-500 mb-4">Start tracking your {{ strtolower($config['name']) }} by adding your first record.</p>
                    <button onclick="openRecordModal()" class="px-4 py-2 bg-{{ $config['color'] }}-500 text-white font-bold text-sm rounded-lg hover:bg-{{ $config['color'] }}-600 transition-colors">
                        Add First Record
                    </button>
                </div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Record Modal -->
    <div id="recordModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden" id="recordModalContent">
            <!-- Modal Header -->
            <div class="p-5 border-b flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $config['icon'] }}</span>
                    <h3 class="font-bold text-lg">Add {{ $config['name'] }}</h3>
                </div>
                <button type="button" onclick="closeRecordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="recordForm" onsubmit="submitRecord(event)" class="p-5">
                <!-- Value Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Value ({{ $config['unit'] }})
                    </label>
                    @if($type === 'blood_pressure')
                        <div class="flex gap-2 items-center">
                            <div class="flex-1">
                                <input type="number" id="systolicValue" name="systolic" placeholder="120" min="60" max="250" 
                                    class="w-full px-3 py-3 text-xl font-bold text-center border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none" required>
                                <p class="text-xs text-gray-500 mt-1 text-center">Systolic</p>
                            </div>
                            <span class="text-2xl text-gray-400">/</span>
                            <div class="flex-1">
                                <input type="number" id="diastolicValue" name="diastolic" placeholder="80" min="40" max="150" 
                                    class="w-full px-3 py-3 text-xl font-bold text-center border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none" required>
                                <p class="text-xs text-gray-500 mt-1 text-center">Diastolic</p>
                            </div>
                        </div>
                    @else
                        <div class="relative">
                            <input 
                                type="number" 
                                id="valueInput"
                                name="value"
                                placeholder="{{ $type === 'sugar_level' ? '100' : ($type === 'temperature' ? '36.5' : '72') }}"
                                @if($type === 'sugar_level')
                                    min="50" max="500"
                                @elseif($type === 'temperature')
                                    min="35" max="42" step="0.1"
                                @elseif($type === 'heart_rate')
                                    min="40" max="200"
                                @endif
                                class="w-full px-4 py-3 text-2xl font-bold text-center border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none"
                                required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">{{ $config['unit'] }}</span>
                        </div>
                    @endif
                    
                    <!-- Normal Range Hint -->
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="font-medium">Normal range:</span>
                        @if($type === 'blood_pressure')
                            120/80 mmHg or lower
                        @elseif($type === 'sugar_level')
                            70-100 mg/dL (fasting)
                        @elseif($type === 'temperature')
                            36.1-37.2°C
                        @elseif($type === 'heart_rate')
                            60-100 bpm (resting)
                        @endif
                    </p>
                </div>

                <!-- Date/Time Picker -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="dateInput" name="date" 
                            value="{{ now()->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <input type="time" id="timeInput" name="time" 
                            value="{{ now()->format('H:i') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none text-sm">
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea 
                        id="notesInput"
                        name="notes"
                        placeholder="Any additional notes..."
                        rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-{{ $config['color'] }}-500 focus:ring-2 focus:ring-{{ $config['color'] }}-200 outline-none resize-none text-sm"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeRecordModal()" class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 py-3 px-4 bg-{{ $config['color'] }}-500 text-white font-medium rounded-lg hover:bg-{{ $config['color'] }}-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const VITAL_TYPE = '{{ $type }}';
        const GOOGLE_FIT_CONNECTED = {{ $googleFitConnected ? 'true' : 'false' }};
        const SUPPORTS_GOOGLE_FIT = {{ $supportsGoogleFit ? 'true' : 'false' }};

        // ==========================================
        // AUTO-SYNC ON PAGE LOAD (ONCE ONLY)
        // ==========================================
        const SYNC_SESSION_KEY = `vitals_synced_${VITAL_TYPE}_${new Date().toDateString()}`;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Only auto-sync once per vital type per day session
            if (GOOGLE_FIT_CONNECTED && SUPPORTS_GOOGLE_FIT && !sessionStorage.getItem(SYNC_SESSION_KEY)) {
                sessionStorage.setItem(SYNC_SESSION_KEY, 'true');
                autoSyncGoogleFit();
            } else if (GOOGLE_FIT_CONNECTED && SUPPORTS_GOOGLE_FIT) {
                // Already synced, just show status
                const statusEl = document.getElementById('autoSyncStatus');
                if (statusEl) {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-green-600">Up to date</span>
                    `;
                }
            }
        });

        async function autoSyncGoogleFit() {
            const statusEl = document.getElementById('autoSyncStatus');
            if (!statusEl) return;

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

                if (data.success && data.synced && Object.keys(data.synced).length > 0) {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-green-600">✓ Synced: ${Object.entries(data.synced).map(([k,v]) => `${k}: ${v}`).join(', ')}</span>
                    `;
                    // Reload to show new data after 1.5s
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    statusEl.innerHTML = `
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Up to date</span>
                    `;
                }
            } catch (error) {
                statusEl.innerHTML = `
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <span class="text-yellow-600">Sync unavailable</span>
                `;
            }
        }

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

                if (data.synced && Object.keys(data.synced).length > 0) {
                    showToast('✅ Synced: ' + Object.entries(data.synced).map(([k,v]) => `${k}: ${v}`).join(', '), 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('Already up to date', 'info');
                }

            } catch (error) {
                showToast('❌ ' + error.message, 'error');
            } finally {
                syncBtn.disabled = false;
                syncBtn.innerHTML = originalContent;
            }
        }

        // ==========================================
        // RECORD MODAL
        // ==========================================
        function openRecordModal() {
            const modal = document.getElementById('recordModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            setTimeout(() => {
                const firstInput = modal.querySelector('input[type="number"]');
                if (firstInput) firstInput.focus();
            }, 100);
        }

        function closeRecordModal() {
            const modal = document.getElementById('recordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            
            // Reset form
            document.getElementById('recordForm').reset();
            document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
            document.getElementById('timeInput').value = new Date().toTimeString().slice(0,5);
        }

        // Close on backdrop click
        document.getElementById('recordModal').addEventListener('click', function(e) {
            if (e.target === this) closeRecordModal();
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRecordModal();
        });

        async function submitRecord(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin">⏳</span> Saving...';

            try {
                const payload = {
                    type: VITAL_TYPE,
                    notes: document.getElementById('notesInput').value || null,
                    measured_at: `${document.getElementById('dateInput').value} ${document.getElementById('timeInput').value}:00`
                };

                @if($type === 'blood_pressure')
                    const systolic = document.getElementById('systolicValue').value;
                    const diastolic = document.getElementById('diastolicValue').value;
                    payload.value_text = `${systolic}/${diastolic}`;
                @else
                    payload.value = parseFloat(document.getElementById('valueInput').value);
                @endif

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

                closeRecordModal();
                showToast('✅ Record saved!', 'success');
                setTimeout(() => window.location.reload(), 500);

            } catch (error) {
                showToast('❌ ' + error.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Save</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            }
        }

        async function deleteRecord(id) {
            if (!confirm('Delete this record?')) return;

            try {
                const response = await fetch(`/my-vitals/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to delete');
                }

                showToast('Record deleted', 'success');
                setTimeout(() => window.location.reload(), 500);

            } catch (error) {
                showToast('❌ ' + error.message, 'error');
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-gray-800';
            toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 ${bgColor} text-white px-6 py-3 rounded-full shadow-lg z-50 font-bold text-sm transform transition-all duration-300 translate-y-4 opacity-0`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transform = 'translateX(-50%) translateY(0)';
                toast.style.opacity = '1';
            }, 10);
            
            setTimeout(() => {
                toast.style.transform = 'translateX(-50%) translateY(20px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    </script>

</body>
</html>
