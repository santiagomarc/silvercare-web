<x-app-layout>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Montserrat', sans-serif; }
        </style>
    </head>

    <div class="min-h-screen bg-[#EBEBEB] py-12 font-sans" x-data="{ editMode: false }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER SECTION -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#000080] rounded-2xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-[900] text-gray-900 tracking-tight">MY PROFILE</h2>
                        <p class="text-gray-500 font-medium">Your personal information</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Success Message -->
                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                            class="flex items-center bg-green-500 text-white px-6 py-3 rounded-2xl shadow-lg shadow-green-200 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span class="font-[700]">Saved!</span>
                        </div>
                    @endif

                    <!-- Edit Button (only show when NOT in edit mode) -->
                    <button x-show="!editMode" @click="editMode = true" type="button"
                        class="flex items-center gap-2 bg-[#000080] text-white px-6 py-3 rounded-2xl font-[700] shadow-lg shadow-blue-900/20 hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Profile
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="space-y-8">
                    
                    <!-- CARD 1: Personal Details (includes contact & address) -->
                    <div class="relative overflow-hidden bg-white rounded-[24px] p-8 shadow-sm border border-gray-100">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -mr-10 -mt-10"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-8">
                                <span class="text-2xl">👤</span>
                                <h3 class="font-[800] text-xl text-gray-900">Personal Details</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                <!-- Full Name -->
                                <div class="md:col-span-2 lg:col-span-1">
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Full Name</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $user->name ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </template>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Email</label>
                                    <p class="px-5 py-4 font-[700] text-gray-900">{{ $user->email ?: '—' }}</p>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Phone Number</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->phone_number ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $profile->phone_number) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </template>
                                </div>

                                <!-- Age -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Age</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->age ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="number" name="age" value="{{ old('age', $profile->age) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </template>
                                </div>

                                <!-- Sex -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Sex</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->sex ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <div class="relative">
                                            <select name="sex" class="w-full appearance-none rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                                <option value="">Select...</option>
                                                <option value="Male" {{ (old('sex', $profile->sex) == 'Male') ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ (old('sex', $profile->sex) == 'Female') ? 'selected' : '' }}>Female</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Height -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Height (cm)</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->height ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="number" step="0.01" name="height" value="{{ old('height', $profile->height) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </template>
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Weight (kg)</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->weight ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="number" step="0.01" name="weight" value="{{ old('weight', $profile->weight) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </template>
                                </div>

                                <!-- Address (full width) -->
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Address</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-gray-900">{{ $profile->address ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <textarea name="address" rows="2" class="w-full resize-none rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">{{ old('address', $profile->address) }}</textarea>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2: Medical Information -->
                    @php
                        function safeImplode($value) {
                            if (is_array($value)) return implode(', ', $value);
                            if (is_string($value)) {
                                $decoded = json_decode($value, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return implode(', ', $decoded);
                                return $value;
                            }
                            return '';
                        }
                        $conditionsVal = safeImplode($profile->medical_conditions);
                        $medsVal = safeImplode($profile->medications);
                        $allergiesVal = safeImplode($profile->allergies);
                    @endphp

                    <div class="relative overflow-hidden bg-white rounded-[24px] p-8 shadow-sm border border-gray-100">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-400 to-rose-500"></div>
                        
                        <div class="relative z-10 mt-2">
                            <div class="flex items-center gap-3 mb-8">
                                <span class="text-2xl">🏥</span>
                                <h3 class="font-[800] text-xl text-gray-900">Medical Information</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <!-- Medical Conditions -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-red-400">Medical Conditions</label>
                                    <template x-if="!editMode">
                                        <div class="px-5 py-4">
                                            @if($conditionsVal)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach(explode(', ', $conditionsVal) as $condition)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-[600] bg-red-50 text-red-700">
                                                            ❤️ {{ trim($condition) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="font-[700] text-gray-400">None specified</p>
                                            @endif
                                        </div>
                                    </template>
                                    <template x-if="editMode">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-red-300">❤️</span>
                                            </div>
                                            <input type="text" name="medical_conditions" value="{{ old('medical_conditions', $conditionsVal) }}" placeholder="e.g. Diabetes, Hypertension"
                                                class="w-full rounded-2xl border-2 border-red-50 bg-red-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-red-200 focus:border-red-400 focus:bg-white focus:ring-0 transition-all">
                                        </div>
                                    </template>
                                </div>

                                <!-- Medications -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-blue-400">Medications</label>
                                    <template x-if="!editMode">
                                        <div class="px-5 py-4">
                                            @if($medsVal)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach(explode(', ', $medsVal) as $med)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-[600] bg-blue-50 text-blue-700">
                                                            💊 {{ trim($med) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="font-[700] text-gray-400">None specified</p>
                                            @endif
                                        </div>
                                    </template>
                                    <template x-if="editMode">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-blue-300">💊</span>
                                            </div>
                                            <input type="text" name="medications" value="{{ old('medications', $medsVal) }}" placeholder="e.g. Metformin, Aspirin"
                                                class="w-full rounded-2xl border-2 border-blue-50 bg-blue-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-blue-200 focus:border-blue-400 focus:bg-white focus:ring-0 transition-all">
                                        </div>
                                    </template>
                                </div>

                                <!-- Allergies -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-amber-500">Allergies</label>
                                    <template x-if="!editMode">
                                        <div class="px-5 py-4">
                                            @if($allergiesVal)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach(explode(', ', $allergiesVal) as $allergy)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-[600] bg-amber-50 text-amber-700">
                                                            ⚠️ {{ trim($allergy) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="font-[700] text-gray-400">None specified</p>
                                            @endif
                                        </div>
                                    </template>
                                    <template x-if="editMode">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-amber-300">⚠️</span>
                                            </div>
                                            <input type="text" name="allergies" value="{{ old('allergies', $allergiesVal) }}" placeholder="e.g. Peanuts, Penicillin"
                                                class="w-full rounded-2xl border-2 border-amber-50 bg-amber-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-amber-200 focus:border-amber-400 focus:bg-white focus:ring-0 transition-all">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: Emergency Contact -->
                    <div class="relative overflow-hidden rounded-[24px] bg-gradient-to-br from-gray-800 to-gray-900 p-8 text-white shadow-xl shadow-gray-900/20">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-white/5 blur-xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 h-32 w-32 rounded-full bg-indigo-500/30 blur-xl"></div>

                        <div class="relative z-10">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="text-2xl">🚨</span>
                                <h3 class="text-xl font-[800] tracking-wide">Emergency Contact</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <!-- Contact Name -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Contact Name</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-white">{{ $profile->emergency_name ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="text" name="emergency_name" value="{{ old('emergency_name', $profile->emergency_name) }}" placeholder="Contact Name"
                                            class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                    </template>
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Phone Number</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-white">{{ $profile->emergency_phone ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $profile->emergency_phone) }}" placeholder="Phone Number"
                                            class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                    </template>
                                </div>

                                <!-- Relationship -->
                                <div>
                                    <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Relationship</label>
                                    <template x-if="!editMode">
                                        <p class="px-5 py-4 font-[700] text-white">{{ $profile->emergency_relationship ?: '—' }}</p>
                                    </template>
                                    <template x-if="editMode">
                                        <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship', $profile->emergency_relationship) }}" placeholder="Relationship"
                                            class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS (only show in edit mode) -->
                    <div x-show="editMode" class="flex justify-end gap-4">
                        <!-- Cancel Button -->
                        <button type="button" @click="editMode = false"
                            class="px-8 py-4 rounded-2xl font-[700] text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all">
                            Cancel
                        </button>

                        <!-- Save Button -->
                        <button type="submit"
                            class="group relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 py-4 px-8 text-lg font-[800] text-white shadow-xl shadow-blue-200 transition-all hover:-translate-y-1 hover:shadow-2xl active:scale-95">
                            <div class="relative z-10 flex items-center justify-center gap-2">
                                <span>SAVE CHANGES</span>
                                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>