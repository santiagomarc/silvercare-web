<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Checklists') }}
            </h2>
            <a href="{{ route('caregiver.checklists.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Task
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

            <!-- Date Selector -->
            <div class="flex items-center justify-between mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-900">Today</h3>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            <!-- Progress Bar -->
            @php
                $total = $checklists->count();
                $completed = $checklists->where('is_completed', true)->count();
                $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                
                $categoryIcons = [
                    'Health' => '❤️',
                    'Exercise' => '🏃',
                    'Nutrition' => '🍎',
                    'Social' => '👥',
                    'Hygiene' => '🧼',
                    'Mental' => '🧠',
                    'Medication' => '💊',
                    'Other' => '📋',
                ];
            @endphp
            <div class="mb-8">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-medium text-gray-700">Daily Progress</span>
                    <span class="text-sm font-bold text-green-600">{{ $completed }}/{{ $total }} ({{ $progress }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <!-- Checklist Items -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @forelse($checklists as $checklist)
                        <li class="group hover:bg-gray-50 transition-colors duration-200">
                            <div class="px-6 py-4 flex items-center">
                                <!-- Toggle Checkbox -->
                                <div class="flex-shrink-0 mr-4">
                                    <form action="{{ route('caregiver.checklists.toggle', $checklist) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-7 h-7 {{ $checklist->is_completed ? 'bg-green-500 border-green-500 text-white' : 'bg-white border-gray-300 hover:border-green-500' }} border-2 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110">
                                            @if($checklist->is_completed)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                <!-- Category Icon -->
                                <div class="flex-shrink-0 mr-4">
                                    <span class="text-2xl">{{ $categoryIcons[$checklist->category] ?? '📋' }}</span>
                                </div>

                                <!-- Task Details -->
                                <div class="flex-grow min-w-0 {{ $checklist->is_completed ? 'opacity-50' : '' }}">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="text-base font-medium text-gray-900 {{ $checklist->is_completed ? 'line-through' : '' }}">{{ $checklist->task }}</h4>
                                        @if($checklist->priority == 'high')
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">High</span>
                                        @elseif($checklist->priority == 'low')
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Low</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $checklist->category }}</span>
                                        @if($checklist->due_date)
                                            <span>📅 {{ $checklist->due_date->format('M d') }}</span>
                                        @endif
                                        @if($checklist->due_time)
                                            <span>🕐 {{ \Carbon\Carbon::parse($checklist->due_time)->format('g:i A') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Due Badge -->
                                @if(!$checklist->is_completed && $checklist->due_date)
                                    @php
                                        $isOverdue = $checklist->due_date->isPast() && !$checklist->due_date->isToday();
                                        $isToday = $checklist->due_date->isToday();
                                    @endphp
                                    <div class="flex-shrink-0 ml-4">
                                        @if($isOverdue)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                        @elseif($isToday)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                Due Today
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                @if($checklist->is_completed)
                                    <div class="flex-shrink-0 ml-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Done
                                        </span>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex-shrink-0 ml-4 flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('caregiver.checklists.edit', $checklist) }}" class="text-gray-400 hover:text-indigo-600 p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('caregiver.checklists.destroy', $checklist) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-12 text-center">
                            <div class="bg-green-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">No tasks yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start by adding a daily task for your elder.</p>
                            <div class="mt-4">
                                <a href="{{ route('caregiver.checklists.create') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add your first task
                                </a>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
