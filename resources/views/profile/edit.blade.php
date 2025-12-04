<x-app-layout>
    <head>
        <!-- MATCH DASHBOARD FONT -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Montserrat', sans-serif; }
        </style>
    </head>

    <div class="min-h-screen bg-[#EBEBEB] py-12 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER SECTION -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#000080] rounded-2xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-[900] text-gray-900 tracking-tight">EDIT PROFILE</h2>
                        <p class="text-gray-500 font-medium">Update your personal information</p>
                    </div>
                </div>
                
                <!-- Success Message (Toast Style) -->
                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                        class="flex items-center bg-green-500 text-white px-6 py-3 rounded-2xl shadow-lg shadow-green-200 transition-all transform animate-fade-in-down">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-[800]">Saved Successfully!</span>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                    
                    <!-- LEFT COLUMN (Personal Details) -->
                    <div class="lg:col-span-8 space-y-8">
                        
                        <!-- CARD 1: Personal Info (Blue Theme) -->
                        <div class="relative overflow-hidden bg-white rounded-[24px] p-8 shadow-sm border border-gray-100 group hover:border-blue-200 transition-all">
                            <!-- Decorative Blobs -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-8">
                                    <span class="text-2xl">👤</span>
                                    <h3 class="font-[800] text-xl text-gray-900">Personal Details</h3>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="md:col-span-2">
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Full Name</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Age</label>
                                        <input type="number" name="age" value="{{ old('age', $profile->age) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Sex</label>
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
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Height (cm)</label>
                                        <input type="number" step="0.01" name="height" value="{{ old('height', $profile->height) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Weight (kg)</label>
                                        <input type="number" step="0.01" name="weight" value="{{ old('weight', $profile->weight) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:ring-0 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: Contact Info (Indigo Theme) -->
                        <div class="relative overflow-hidden bg-white rounded-[24px] p-8 shadow-sm border border-gray-100 group hover:border-indigo-200 transition-all">
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-50 rounded-tr-[80px] -ml-6 -mb-6 transition-transform group-hover:scale-110"></div>

                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-8">
                                    <span class="text-2xl">📞</span>
                                    <h3 class="font-[800] text-xl text-gray-900">Contact Info</h3>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Phone Number</label>
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $profile->phone_number) }}" 
                                            class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-indigo-500 focus:bg-white focus:ring-0 outline-none">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-[800] uppercase tracking-wider text-gray-400">Address</label>
                                        <textarea name="address" rows="3" class="w-full resize-none rounded-xl border-2 border-gray-100 bg-gray-50 px-5 py-4 font-[700] text-gray-900 transition-all focus:border-indigo-500 focus:bg-white focus:ring-0 outline-none">{{ old('address', $profile->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Medical & Actions) -->
                    <div class="lg:col-span-4 space-y-8">
                        
                        <!-- CARD 3: Medical History (Rose/Red Theme) -->
                        <div class="relative overflow-hidden bg-white rounded-[24px] p-8 shadow-sm border border-gray-100 group hover:border-red-200 transition-all">
                            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-400 to-rose-500"></div>
                            
                            <div class="flex items-center gap-3 mb-8 mt-2">
                                <span class="text-2xl">🏥</span>
                                <h3 class="font-[800] text-xl text-gray-900">Medical History</h3>
                            </div>

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
                                $conditionsVal = old('medical_conditions', safeImplode($profile->medical_conditions));
                                $medsVal = old('medications', safeImplode($profile->medications));
                                $allergiesVal = old('allergies', safeImplode($profile->allergies));
                            @endphp

                            <div class="space-y-5">
                                <!-- Conditions -->
                                <div>
                                    <label class="mb-2 ml-1 block text-xs font-[800] uppercase tracking-wider text-red-400">Conditions</label>
                                    <div class="relative group/input">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-red-300">❤️</span>
                                        </div>
                                        <input type="text" name="medical_conditions" value="{{ $conditionsVal }}" placeholder="e.g. Asthma"
                                            class="w-full rounded-2xl border-2 border-red-50 bg-red-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-red-200 focus:border-red-400 focus:bg-white focus:ring-0 transition-all">
                                    </div>
                                </div>

                                <!-- Medications -->
                                <div>
                                    <label class="mb-2 ml-1 block text-xs font-[800] uppercase tracking-wider text-blue-400">Medications</label>
                                    <div class="relative group/input">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-blue-300">💊</span>
                                        </div>
                                        <input type="text" name="medications" value="{{ $medsVal }}" placeholder="e.g. Ibuprofen"
                                            class="w-full rounded-2xl border-2 border-blue-50 bg-blue-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-blue-200 focus:border-blue-400 focus:bg-white focus:ring-0 transition-all">
                                    </div>
                                </div>

                                <!-- Allergies -->
                                <div>
                                    <label class="mb-2 ml-1 block text-xs font-[800] uppercase tracking-wider text-amber-500">Allergies</label>
                                    <div class="relative group/input">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-amber-300">⚠️</span>
                                        </div>
                                        <input type="text" name="allergies" value="{{ $allergiesVal }}" placeholder="e.g. Peanuts"
                                            class="w-full rounded-2xl border-2 border-amber-50 bg-amber-50/30 pl-12 pr-5 py-4 font-[700] text-gray-800 placeholder-amber-200 focus:border-amber-400 focus:bg-white focus:ring-0 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 4: Emergency (Dark Gradient Theme) -->
                        <div class="relative overflow-hidden rounded-[24px] bg-gradient-to-br from-gray-800 to-gray-900 p-8 text-white shadow-xl shadow-gray-900/20">
                            <!-- Abstract Circles -->
                            <div class="absolute top-0 right-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-white/5 blur-xl"></div>
                            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 h-32 w-32 rounded-full bg-indigo-500/30 blur-xl"></div>

                            <div class="relative z-10">
                                <div class="mb-6 flex items-center gap-3">
                                    <span class="text-2xl">🚨</span>
                                    <h3 class="text-xl font-[800] tracking-wide">Emergency</h3>
                                </div>

                                <div class="space-y-4">
                                    <input type="text" name="emergency_name" value="{{ old('emergency_name', $profile->emergency_name) }}" placeholder="Contact Name"
                                        class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                    
                                    <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $profile->emergency_phone) }}" placeholder="Phone Number"
                                        class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                    
                                    <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship', $profile->emergency_relationship) }}" placeholder="Relationship"
                                        class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white font-[600] placeholder-gray-400 backdrop-blur-sm transition-all focus:bg-white/20 focus:ring-2 focus:ring-yellow-400">
                                </div>
                            </div>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="sticky bottom-6 z-20">
                            <button type="submit" class="group relative w-full overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 py-4 px-8 text-lg font-[800] text-white shadow-xl shadow-blue-200 transition-all hover:-translate-y-1 hover:shadow-2xl active:scale-95">
                                <div class="relative z-10 flex items-center justify-center gap-2">
                                    <span>SAVE CHANGES</span>
                                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>