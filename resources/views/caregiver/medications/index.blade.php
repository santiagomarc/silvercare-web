<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Medications') }}
            </h2>
            <a href="{{ route('caregiver.medications.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Medication
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Medications List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($medications as $medication)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-indigo-50 text-indigo-600 p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('caregiver.medications.edit', $medication->id) }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('caregiver.medications.destroy', $medication->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $medication->name }}</h3>
                        
                        <!-- Dosage -->
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            <span class="font-medium">{{ $medication->dosage }} {{ $medication->dosage_unit }}</span>
                        </div>

                        <!-- Days of Week -->
                        @if(!empty($medication->days_of_week))
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Schedule</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $index => $shortDay)
                                        @php $fullDay = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$index]; @endphp
                                        <span class="px-2 py-0.5 text-xs rounded-full {{ in_array($fullDay, $medication->days_of_week) ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $shortDay }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Times of Day -->
                        @if(!empty($medication->times_of_day))
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Times</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($medication->times_of_day as $time)
                                        <span class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 text-xs rounded-lg">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ \Carbon\Carbon::parse($time)->format('g:i A') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Instructions -->
                        @if($medication->instructions)
                            <p class="text-sm text-gray-500 italic border-t border-gray-100 pt-3 mt-3">{{ Str::limit($medication->instructions, 80) }}</p>
                        @endif
                    </div>

                    <!-- Footer with stock info -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs font-medium {{ $medication->is_active ? 'text-green-600 bg-green-100' : 'text-gray-600 bg-gray-200' }} px-2 py-1 rounded-full">
                            {{ $medication->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($medication->track_inventory)
                            <span class="text-xs {{ ($medication->current_stock <= ($medication->low_stock_threshold ?? 5)) ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                                📦 Stock: {{ $medication->current_stock ?? 0 }}
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-indigo-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No medications yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding a medication schedule for your elder.</p>
                    <div class="mt-6">
                        <a href="{{ route('caregiver.medications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Medication
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
