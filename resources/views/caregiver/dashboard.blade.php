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
                <p class="text-gray-600 mt-2">Here's what's happening with <span class="font-semibold text-indigo-600">{{ $elderly ? $elderly->user->name : 'your loved one' }}</span> today.</p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Vitals & Mood -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Mood Widget -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <span class="bg-purple-100 text-purple-600 p-2 rounded-lg mr-3">😊</span>
                                    Current Mood
                                </h3>
                                <span class="text-xs text-gray-500">{{ $mood ? $mood->measured_at->diffForHumans() : 'No data' }}</span>
                            </div>
                            
                            @if($mood)
                                <div class="flex items-center">
                                    <div class="text-4xl mr-4">{{ $mood->value_text }}</div> <!-- Assuming value_text holds emoji or text -->
                                    <div>
                                        <p class="text-gray-900 font-medium text-lg">{{ ucfirst($mood->value) }}</p>
                                        <p class="text-gray-500 text-sm">{{ $mood->notes ?? 'No notes added.' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No mood recorded yet.</p>
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
                                <span class="text-xs text-gray-400">{{ $vitals['heart_rate'] ? $vitals['heart_rate']->measured_at->diffForHumans() : '--' }}</span>
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Heart Rate</h4>
                            <div class="mt-1 flex items-baseline">
                                <span class="text-2xl font-bold text-gray-900">{{ $vitals['heart_rate'] ? $vitals['heart_rate']->value : '--' }}</span>
                                <span class="ml-1 text-sm text-gray-500">bpm</span>
                            </div>
                        </div>

                        <!-- Blood Pressure -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-blue-50 text-blue-500 p-2 rounded-lg group-hover:bg-blue-100 transition-colors">
                                    🩺
                                </div>
                                <span class="text-xs text-gray-400">{{ $vitals['blood_pressure'] ? $vitals['blood_pressure']->measured_at->diffForHumans() : '--' }}</span>
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Blood Pressure</h4>
                            <div class="mt-1 flex items-baseline">
                                <span class="text-2xl font-bold text-gray-900">{{ $vitals['blood_pressure'] ? $vitals['blood_pressure']->value_text : '--' }}</span> <!-- BP is usually text like 120/80 -->
                                <span class="ml-1 text-sm text-gray-500">mmHg</span>
                            </div>
                        </div>

                        <!-- Sugar Level -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-pink-50 text-pink-500 p-2 rounded-lg group-hover:bg-pink-100 transition-colors">
                                    🍬
                                </div>
                                <span class="text-xs text-gray-400">{{ $vitals['sugar_level'] ? $vitals['sugar_level']->measured_at->diffForHumans() : '--' }}</span>
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Sugar Level</h4>
                            <div class="mt-1 flex items-baseline">
                                <span class="text-2xl font-bold text-gray-900">{{ $vitals['sugar_level'] ? $vitals['sugar_level']->value : '--' }}</span>
                                <span class="ml-1 text-sm text-gray-500">mg/dL</span>
                            </div>
                        </div>

                        <!-- Temperature -->
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="bg-orange-50 text-orange-500 p-2 rounded-lg group-hover:bg-orange-100 transition-colors">
                                    🌡️
                                </div>
                                <span class="text-xs text-gray-400">{{ $vitals['temperature'] ? $vitals['temperature']->measured_at->diffForHumans() : '--' }}</span>
                            </div>
                            <h4 class="text-gray-500 text-sm font-medium">Temperature</h4>
                            <div class="mt-1 flex items-baseline">
                                <span class="text-2xl font-bold text-gray-900">{{ $vitals['temperature'] ? $vitals['temperature']->value : '--' }}</span>
                                <span class="ml-1 text-sm text-gray-500">°C</span>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Placeholder -->
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
                    
                    <!-- Core Management Features -->
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

                    <!-- Recent Activity (Placeholder) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="bg-green-100 rounded-full p-1 mr-3 mt-1">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800 font-medium">Morning Medication Taken</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-blue-100 rounded-full p-1 mr-3 mt-1">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800 font-medium">Vitals Updated</p>
                                    <p class="text-xs text-gray-500">4 hours ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
