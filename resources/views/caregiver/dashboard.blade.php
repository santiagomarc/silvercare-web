<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Caregiver Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">👋 Welcome, Caregiver!</h3>
                    <p class="mb-4">This is your caregiver dashboard. You can monitor your elderly loved one's health and activities here.</p>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Dashboard features coming soon! We're building medication tracking, health monitoring, and more.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800">💊 Medication Schedule</h4>
                            <p class="text-sm text-gray-600 mt-2">Track and manage medications</p>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800">❤️ Health Metrics</h4>
                            <p class="text-sm text-gray-600 mt-2">Monitor vital signs and health data</p>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800">📅 Calendar & Events</h4>
                            <p class="text-sm text-gray-600 mt-2">Manage appointments and activities</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
