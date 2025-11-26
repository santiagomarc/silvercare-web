<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Checklist Task') }}
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

                    <form method="POST" action="{{ route('caregiver.checklists.store') }}">
                        @csrf

                        <!-- Task Name -->
                        <div class="mb-6">
                            <label for="task" class="block text-sm font-medium text-gray-700 mb-1">Task <span class="text-red-500">*</span></label>
                            <input type="text" name="task" id="task" value="{{ old('task') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" placeholder="e.g. Take afternoon walk, Drink water, Do stretching exercises" required>
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
                                @endphp
                                @foreach($categories as $catName => $catData)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="category" value="{{ $catName }}" class="peer sr-only" {{ old('category') == $catName ? 'checked' : '' }} required>
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
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                            </div>

                            <div>
                                <label for="due_time" class="block text-sm font-medium text-gray-700 mb-1">Due Time (Optional)</label>
                                <input type="time" name="due_time" id="due_time" value="{{ old('due_time') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if no specific time</p>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Priority</label>
                            <div class="flex flex-wrap gap-3">
                                @php $priorities = ['Low' => 'bg-gray-100 text-gray-700', 'Medium' => 'bg-yellow-100 text-yellow-700', 'High' => 'bg-red-100 text-red-700']; @endphp
                                @foreach($priorities as $priority => $classes)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="priority" value="{{ strtolower($priority) }}" class="peer sr-only" {{ old('priority', 'medium') == strtolower($priority) ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-full border-2 border-gray-200 {{ $classes }} font-medium text-sm transition-all duration-200 peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-200 hover:border-green-300">
                                            {{ $priority }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-8">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" placeholder="Any additional notes or reminders...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Quick Add Templates -->
                        <div class="mb-8 bg-green-50 p-4 rounded-lg border border-green-100">
                            <h3 class="text-sm font-medium text-green-800 mb-3">Quick Templates</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setTemplate('Take morning walk', 'Exercise')" class="px-3 py-1 bg-white text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-100 transition-colors">🏃 Morning Walk</button>
                                <button type="button" onclick="setTemplate('Drink 8 glasses of water', 'Health')" class="px-3 py-1 bg-white text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-100 transition-colors">💧 Hydration</button>
                                <button type="button" onclick="setTemplate('Do stretching exercises', 'Exercise')" class="px-3 py-1 bg-white text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-100 transition-colors">🧘 Stretching</button>
                                <button type="button" onclick="setTemplate('Call family member', 'Social')" class="px-3 py-1 bg-white text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-100 transition-colors">📞 Family Call</button>
                                <button type="button" onclick="setTemplate('Read for 30 minutes', 'Mental')" class="px-3 py-1 bg-white text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-100 transition-colors">📖 Reading</button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('caregiver.checklists.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setTemplate(task, category) {
            document.getElementById('task').value = task;
            document.querySelector(`input[name="category"][value="${category}"]`).checked = true;
        }
    </script>
</x-app-layout>
