<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Medication') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
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

                    <form method="POST" action="{{ route('caregiver.medications.update', $medication) }}" id="medicationForm">
                        @csrf
                        @method('PUT')

                        <!-- Medication Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Medication Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $medication->name) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. Lisinopril, Metformin" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Dosage -->
                            <div>
                                <label for="dosage" class="block text-sm font-medium text-gray-700 mb-1">Dosage <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="text" name="dosage" id="dosage" value="{{ old('dosage', $medication->dosage) }}" class="w-2/3 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. 10, 500" required>
                                    <select name="dosage_unit" class="w-1/3 rounded-r-lg border-l-0 border-gray-300 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        @php $unit = old('dosage_unit', $medication->dosage_unit ?? 'mg'); @endphp
                                        <option value="mg" {{ $unit == 'mg' ? 'selected' : '' }}>mg</option>
                                        <option value="ml" {{ $unit == 'ml' ? 'selected' : '' }}>ml</option>
                                        <option value="tablet" {{ $unit == 'tablet' ? 'selected' : '' }}>tablet</option>
                                        <option value="capsule" {{ $unit == 'capsule' ? 'selected' : '' }}>capsule</option>
                                        <option value="puff" {{ $unit == 'puff' ? 'selected' : '' }}>puff</option>
                                        <option value="drop" {{ $unit == 'drop' ? 'selected' : '' }}>drop</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $medication->start_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <!-- Days of Week Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Recurrence Days <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">Select which days the medication should be taken</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $selectedDays = old('days_of_week', $medication->days_of_week ?? []);
                                @endphp
                                @foreach($days as $day)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="days_of_week[]" value="{{ $day }}" class="peer sr-only" {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                        <div class="px-4 py-2 rounded-full border-2 border-gray-300 bg-white text-gray-700 font-medium text-sm transition-all duration-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white hover:border-indigo-300">
                                            {{ substr($day, 0, 3) }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-2 flex gap-2">
                                <button type="button" onclick="selectAllDays()" class="text-xs text-indigo-600 hover:underline">Select All</button>
                                <span class="text-gray-300">|</span>
                                <button type="button" onclick="selectWeekdays()" class="text-xs text-indigo-600 hover:underline">Weekdays</button>
                                <span class="text-gray-300">|</span>
                                <button type="button" onclick="clearDays()" class="text-xs text-indigo-600 hover:underline">Clear</button>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Time Slots <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">Add the specific times when medication should be taken</p>
                            
                            <div id="timeSlotContainer" class="space-y-2 mb-3">
                                <!-- Time slots will be added here -->
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <input type="time" id="newTimeInput" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <button type="button" onclick="addTimeSlot()" class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Time
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Examples: 08:00 (morning), 14:00 (afternoon), 21:00 (night)</p>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-6">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Instructions (Optional)</label>
                            <textarea name="instructions" id="instructions" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. Take with food, do not crush, avoid grapefruit...">{{ old('instructions', $medication->instructions) }}</textarea>
                        </div>

                        <!-- Inventory Tracking -->
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-medium text-gray-900">Inventory Tracking (Optional)</h3>
                                <div class="flex items-center">
                                    <input type="checkbox" name="track_inventory" id="track_inventory" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('track_inventory', $medication->track_inventory) ? 'checked' : '' }}>
                                    <label for="track_inventory" class="ml-2 text-sm text-gray-600">Enable tracking</label>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="current_stock" class="block text-xs font-medium text-gray-500 mb-1">Current Stock</label>
                                    <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock', $medication->current_stock) }}" min="0" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" placeholder="e.g. 30">
                                </div>
                                <div>
                                    <label for="low_stock_threshold" class="block text-xs font-medium text-gray-500 mb-1">Low Stock Alert At</label>
                                    <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $medication->low_stock_threshold ?? 5) }}" min="0" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('caregiver.medications.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Update Medication
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize time slots from existing medication data
        let timeSlots = @json(old('times_of_day', $medication->times_of_day ?? []));

        function addTimeSlot() {
            const input = document.getElementById('newTimeInput');
            const time = input.value;
            
            if (!time) {
                alert('Please select a time first');
                return;
            }
            
            if (timeSlots.includes(time)) {
                alert('This time slot already exists');
                return;
            }
            
            timeSlots.push(time);
            timeSlots.sort();
            renderTimeSlots();
            input.value = '';
        }

        function removeTimeSlot(time) {
            timeSlots = timeSlots.filter(t => t !== time);
            renderTimeSlots();
        }

        function renderTimeSlots() {
            const container = document.getElementById('timeSlotContainer');
            container.innerHTML = '';
            
            if (timeSlots.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-400 italic">No time slots added yet</p>';
                return;
            }
            
            timeSlots.forEach(time => {
                const div = document.createElement('div');
                div.className = 'inline-flex items-center bg-indigo-50 text-indigo-700 px-3 py-2 rounded-lg mr-2 mb-2';
                div.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">${formatTime(time)}</span>
                    <input type="hidden" name="times_of_day[]" value="${time}">
                    <button type="button" onclick="removeTimeSlot('${time}')" class="ml-2 text-indigo-400 hover:text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function formatTime(time24) {
            const [hours, minutes] = time24.split(':');
            const h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = h % 12 || 12;
            return `${h12}:${minutes} ${ampm}`;
        }

        function selectAllDays() {
            document.querySelectorAll('input[name="days_of_week[]"]').forEach(cb => cb.checked = true);
        }

        function selectWeekdays() {
            const weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            document.querySelectorAll('input[name="days_of_week[]"]').forEach(cb => {
                cb.checked = weekdays.includes(cb.value);
            });
        }

        function clearDays() {
            document.querySelectorAll('input[name="days_of_week[]"]').forEach(cb => cb.checked = false);
        }

        // Form validation
        document.getElementById('medicationForm').addEventListener('submit', function(e) {
            const checkedDays = document.querySelectorAll('input[name="days_of_week[]"]:checked');
            if (checkedDays.length === 0) {
                e.preventDefault();
                alert('Please select at least one day for the medication schedule');
                return;
            }
            
            if (timeSlots.length === 0) {
                e.preventDefault();
                alert('Please add at least one time slot');
                return;
            }
        });

        // Initial render
        renderTimeSlots();
    </script>
</x-app-layout>
