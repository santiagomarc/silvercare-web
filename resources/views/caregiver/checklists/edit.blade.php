<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Checklist Task') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('caregiver.checklists.update', $checklist) }}">
                        @csrf
                        @method('PUT')

                        <!-- Task Name -->
                        <div class="mb-6">
                            <label for="task" class="block text-sm font-medium text-gray-700 mb-1">Task <span class="text-red-500">*</span></label>
                            <input type="text" name="task" id="task" value="{{ old('task', $checklist->task) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" placeholder="e.g. Take afternoon walk, Drink water, Do stretching exercises" required>
                            <p class="text-xs text-gray-500 mt-1">Describe what needs to be done</p>
                        </div>

                        <!-- Category Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Category <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $categories = [
                                        'Health' => ['icon' => '❤️', 'color' => 'red'],
                                        'Exercise' => ['icon' => '🏃', 'color' => 'orange'],
                                        'Nutrition' => ['icon' => '🍎', 'color' => 'green'],
                                        'Social' => ['icon' => '👥', 'color' => 'blue'],
                                        'Hygiene' => ['icon' => '🧼', 'color' => 'cyan'],
                                        'Mental' => ['icon' => '🧠', 'color' => 'purple'],
                                        'Medication' => ['icon' => '💊', 'color' => 'pink'],
                                        'Other' => ['icon' => '📋', 'color' => 'gray'],
                                    ];
                                    $selectedCategory = old('category', $checklist->category);
                                @endphp
                                @foreach($categories as $catName => $catData)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="category" value="{{ $catName }}" class="peer sr-only" {{ $selectedCategory == $catName ? 'checked' : '' }} required>
                                        <div class="p-3 rounded-xl border-2 border-gray-200 bg-white text-center transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
                                            <div class="text-2xl mb-1">{{ $catData['icon'] }}</div>
                                            <div class="text-xs font-medium text-gray-700">{{ $catName }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Due Date and Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $checklist->due_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                            </div>

                            <div>
                                <label for="due_time" class="block text-sm font-medium text-gray-700 mb-1">Due Time (Optional)</label>
                                <input type="time" name="due_time" id="due_time" value="{{ old('due_time', $checklist->due_time ? \Carbon\Carbon::parse($checklist->due_time)->format('H:i') : '') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if no specific time</p>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Priority</label>
                            <div class="flex flex-wrap gap-3">
                                @php 
                                    $priorities = ['Low' => 'bg-gray-100 text-gray-700', 'Medium' => 'bg-yellow-100 text-yellow-700', 'High' => 'bg-red-100 text-red-700'];
                                    $selectedPriority = old('priority', $checklist->priority ?? 'medium');
                                @endphp
                                @foreach($priorities as $priority => $classes)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="priority" value="{{ strtolower($priority) }}" class="peer sr-only" {{ $selectedPriority == strtolower($priority) ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-full border-2 border-gray-200 {{ $classes }} font-medium text-sm transition-all duration-200 peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-200 hover:border-green-300">
                                            {{ $priority }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Completion Status -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Task Status</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($checklist->is_completed)
                                            Completed on {{ $checklist->completed_at?->format('M d, Y \a\t h:i A') }}
                                        @else
                                            Not yet completed
                                        @endif
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_completed" value="1" class="sr-only peer" {{ old('is_completed', $checklist->is_completed) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">Completed</span>
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-8">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" placeholder="Any additional notes or reminders...">{{ old('notes', $checklist->notes) }}</textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('caregiver.checklists.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Update Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
