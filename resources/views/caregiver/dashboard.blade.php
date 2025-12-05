<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Caregiver Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-600 mt-2">Here's what's happening with <span class="font-semibold text-indigo-600">{{ $elderly ? ($elderlyUser->name ?? $elderly->user->name) : 'your loved one' }}</span> today.</p>
            </div>

            @if(!$elderly)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No elderly profile is currently associated with your account. Please contact support or complete the setup.
                            </p>
                        </div>
                    </div>
                </div>
            @else

            <!-- Today's Overview Stats -->
            @if(!empty($stats))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Medication Adherence -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Medication Today</p>
                            @if($stats['doses_total'] > 0)
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['doses_taken'] }}/{{ $stats['doses_total'] }}</p>
                                <p class="text-xs text-gray-500">doses taken</p>
                            @else
                                <p class="text-lg font-medium text-gray-400">No doses scheduled</p>
                            @endif
                        </div>
                        <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $stats['medication_adherence'] === 100 ? 'bg-green-100' : ($stats['medication_adherence'] >= 50 ? 'bg-yellow-100' : 'bg-gray-100') }}">
                            @if($stats['medication_adherence'] !== null)
                                <span class="text-lg font-bold {{ $stats['medication_adherence'] === 100 ? 'text-green-600' : ($stats['medication_adherence'] >= 50 ? 'text-yellow-600' : 'text-gray-500') }}">{{ $stats['medication_adherence'] }}%</span>
                            @else
                                <span class="text-xl">💊</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Task Completion -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Tasks Today</p>
                            @if($stats['tasks_total'] > 0)
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['tasks_completed'] }}/{{ $stats['tasks_total'] }}</p>
                                <p class="text-xs text-gray-500">completed</p>
                            @else
                                <p class="text-lg font-medium text-gray-400">No tasks due</p>
                            @endif
                        </div>
                        <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $stats['task_completion'] === 100 ? 'bg-green-100' : ($stats['task_completion'] >= 50 ? 'bg-yellow-100' : 'bg-gray-100') }}">
                            @if($stats['task_completion'] !== null)
                                <span class="text-lg font-bold {{ $stats['task_completion'] === 100 ? 'text-green-600' : ($stats['task_completion'] >= 50 ? 'text-yellow-600' : 'text-gray-500') }}">{{ $stats['task_completion'] }}%</span>
                            @else
                                <span class="text-xl">✅</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Vitals Recorded -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Vitals Recorded</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['vitals_recorded'] }}/{{ $stats['vitals_total'] }}</p>
                            <p class="text-xs text-gray-500">types today</p>
                        </div>
                        <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $stats['vitals_recorded'] === $stats['vitals_total'] ? 'bg-green-100' : ($stats['vitals_recorded'] > 0 ? 'bg-blue-100' : 'bg-gray-100') }}">
                            <span class="text-xl">📊</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Vitals & Mood -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Mood Widget -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">😊</span>
                                    Today's Mood
                                </h3>
                                @if($mood)
                                    <span class="text-xs text-gray-500">{{ $mood->measured_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            
                            @if($mood)
                                @php
                                    $moodEmojis = [1 => '😢', 2 => '😕', 3 => '😐', 4 => '🙂', 5 => '😊'];
                                    $moodLabels = [1 => 'Poor', 2 => 'Fair', 3 => 'Okay', 4 => 'Good', 5 => 'Excellent'];
                                    $moodColors = [1 => 'red', 2 => 'orange', 3 => 'yellow', 4 => 'green', 5 => 'green'];
                                    $moodValue = (int)$mood->value;
                                @endphp
                                <div class="flex items-center">
                                    <div class="text-5xl mr-4">{{ $moodEmojis[$moodValue] ?? '😐' }}</div>
                                    <div>
                                        <p class="text-gray-900 font-semibold text-xl">{{ $moodLabels[$moodValue] ?? 'Unknown' }}</p>
                                        <p class="text-gray-500 text-sm mt-1">{{ $mood->notes ?? 'No notes added.' }}</p>
                                    </div>
                                </div>
                                <!-- Mood Scale Indicator -->
                                <div class="mt-4 flex items-center space-x-2">
                                    @foreach($moodEmojis as $level => $emoji)
                                        <div class="flex-1 h-2 rounded-full {{ $moodValue >= $level ? 'bg-purple-400' : 'bg-gray-200' }}"></div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <span class="text-4xl mb-2 block opacity-50">😶</span>
                                    <p class="text-gray-400 italic">No mood recorded today</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Vitals Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Heart Rate -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-red-50 text-red-500 p-2 rounded-lg group-hover:bg-red-100 transition-colors">
                                    ❤️
                                </div>
                                @if($vitals['heart_rate'])
                                    <div class="flex items-center space-x-2">
                                        @if($vitals['heart_rate']['metric']->source === 'google_fit')
                                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Google Fit</span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $vitals['heart_rate']['status']['bg'] }} {{ $vitals['heart_rate']['status']['text'] }}">
                                            {{ $vitals['heart_rate']['status']['label'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Heart Rate</h4>
                            @if($vitals['heart_rate'])
                                <div class="mt-1 flex items-baseline">
                                    <span class="text-2xl font-bold text-gray-900">{{ intval($vitals['heart_rate']['metric']->value) }}</span>
                                    <span class="ml-1 text-sm text-gray-500">bpm</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $vitals['heart_rate']['metric']->measured_at->diffForHumans() }}</p>
                            @else
                                <div class="mt-1">
                                    <span class="text-lg text-gray-300 font-medium">No record today</span>
                                </div>
                            @endif
                        </div>

                        <!-- Blood Pressure -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-blue-50 text-blue-500 p-2 rounded-lg group-hover:bg-blue-100 transition-colors">
                                    🩺
                                </div>
                                @if($vitals['blood_pressure'])
                                    <div class="flex items-center space-x-2">
                                        @if($vitals['blood_pressure']['metric']->source === 'google_fit')
                                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Google Fit</span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $vitals['blood_pressure']['status']['bg'] }} {{ $vitals['blood_pressure']['status']['text'] }}">
                                            {{ $vitals['blood_pressure']['status']['label'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Blood Pressure</h4>
                            @if($vitals['blood_pressure'])
                                <div class="mt-1 flex items-baseline">
                                    <span class="text-2xl font-bold text-gray-900">{{ $vitals['blood_pressure']['metric']->value_text }}</span>
                                    <span class="ml-1 text-sm text-gray-500">mmHg</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $vitals['blood_pressure']['metric']->measured_at->diffForHumans() }}</p>
                            @else
                                <div class="mt-1">
                                    <span class="text-lg text-gray-300 font-medium">No record today</span>
                                </div>
                            @endif
                        </div>

                        <!-- Sugar Level -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-pink-50 text-pink-500 p-2 rounded-lg group-hover:bg-pink-100 transition-colors">
                                    🍬
                                </div>
                                @if($vitals['sugar_level'])
                                    <div class="flex items-center space-x-2">
                                        @if($vitals['sugar_level']['metric']->source === 'google_fit')
                                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Google Fit</span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $vitals['sugar_level']['status']['bg'] }} {{ $vitals['sugar_level']['status']['text'] }}">
                                            {{ $vitals['sugar_level']['status']['label'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Sugar Level</h4>
                            @if($vitals['sugar_level'])
                                <div class="mt-1 flex items-baseline">
                                    <span class="text-2xl font-bold text-gray-900">{{ intval($vitals['sugar_level']['metric']->value) }}</span>
                                    <span class="ml-1 text-sm text-gray-500">mg/dL</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $vitals['sugar_level']['metric']->measured_at->diffForHumans() }}</p>
                            @else
                                <div class="mt-1">
                                    <span class="text-lg text-gray-300 font-medium">No record today</span>
                                </div>
                            @endif
                        </div>

                        <!-- Temperature -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-orange-50 text-orange-500 p-2 rounded-lg group-hover:bg-orange-100 transition-colors">
                                    🌡️
                                </div>
                                @if($vitals['temperature'])
                                    <div class="flex items-center space-x-2">
                                        @if($vitals['temperature']['metric']->source === 'google_fit')
                                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Google Fit</span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $vitals['temperature']['status']['bg'] }} {{ $vitals['temperature']['status']['text'] }}">
                                            {{ $vitals['temperature']['status']['label'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Temperature</h4>
                            @if($vitals['temperature'])
                                <div class="mt-1 flex items-baseline">
                                    <span class="text-2xl font-bold text-gray-900">{{ number_format($vitals['temperature']['metric']->value, 1) }}</span>
                                    <span class="ml-1 text-sm text-gray-500">°C</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $vitals['temperature']['metric']->measured_at->diffForHumans() }}</p>
                            @else
                                <div class="mt-1">
                                    <span class="text-lg text-gray-300 font-medium">No record today</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Analytics Link -->
                    <a href="{{ route('caregiver.analytics') }}" class="block bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold mb-2">Health Analytics</h3>
                                    <p class="text-indigo-100 text-sm">View detailed trends and reports over time.</p>
                                </div>
                                <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>

                <!-- Right Column: Actions & Activity -->
                <div class="space-y-6">
                    
                    <!-- Care Management -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Care Management</h3>
                        
                        <!-- Manage Medications Button -->
                        <a href="{{ route('caregiver.medications.index') }}" class="group block bg-white rounded-xl p-6 shadow-sm border border-blue-100 hover:shadow-md hover:border-blue-300 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div>
                                    <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Medications</h4>
                                    <p class="text-sm text-gray-500 mt-1 group-hover:text-blue-600/80">Manage dosage & schedules</p>
                                </div>
                                <div class="text-blue-400 group-hover:translate-x-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </a>

                        <!-- Daily Checklists Button -->
                        <a href="{{ route('caregiver.checklists.index') }}" class="group block bg-white rounded-xl p-6 shadow-sm border border-green-100 hover:shadow-md hover:border-green-300 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div>
                                    <div class="bg-green-100 text-green-600 w-12 h-12 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 group-hover:text-green-700 transition-colors">Checklists</h4>
                                    <p class="text-sm text-gray-500 mt-1 group-hover:text-green-600/80">Daily tasks & routines</p>
                                </div>
                                <div class="text-green-400 group-hover:translate-x-1 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                            <span class="text-xs text-gray-400">Last 7 days</span>
                        </div>
                        
                        @if($recentActivity->count() > 0)
                            <ul class="space-y-3 max-h-80 overflow-y-auto">
                                @foreach($recentActivity as $activity)
                                    <li class="flex items-start py-2 border-b border-gray-50 last:border-0">
                                        <div class="text-xl mr-3">{{ $activity['icon'] }}</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 font-medium truncate">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['subtitle'] }}</p>
                                        </div>
                                        <div class="text-xs text-gray-400 ml-2 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans(null, true, true) }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2 opacity-30">📭</div>
                                <p class="text-gray-400 text-sm">No recent activity</p>
                                <p class="text-gray-300 text-xs mt-1">Activity will appear here as it happens</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Health Legend -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Health Status Legend</h4>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-gray-600">Normal</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                <span class="text-gray-600">Elevated</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-orange-500 mr-2"></span>
                                <span class="text-gray-600">High/Fever</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                <span class="text-gray-600">Critical</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                <span class="text-gray-600">Low</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
