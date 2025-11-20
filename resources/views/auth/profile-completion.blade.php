<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Complete Your Profile</h2>
        <p class="text-sm text-gray-600 text-center mt-2">Help us personalize your experience</p>
    </div>

    <div x-data="{
        currentStep: 1,
        formData: {
            age: '',
            weight: '',
            height: '',
            emergency_name: '',
            emergency_phone: '',
            emergency_relationship: '',
            conditions: '',
            medications: '',
            allergies: ''
        },
        nextStep() {
            if (this.currentStep < 3) this.currentStep++;
        },
        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        }
    }">
        
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <!-- Step 1 -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                         :class="currentStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'">
                        1
                    </div>
                    <span class="text-xs mt-2" :class="currentStep >= 1 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Personal</span>
                </div>
                
                <!-- Connector -->
                <div class="flex-1 h-0.5" :class="currentStep >= 2 ? 'bg-indigo-600' : 'bg-gray-200'"></div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                         :class="currentStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'">
                        2
                    </div>
                    <span class="text-xs mt-2" :class="currentStep >= 2 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Emergency</span>
                </div>
                
                <!-- Connector -->
                <div class="flex-1 h-0.5" :class="currentStep >= 3 ? 'bg-indigo-600' : 'bg-gray-200'"></div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                         :class="currentStep >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'">
                        3
                    </div>
                    <span class="text-xs mt-2" :class="currentStep >= 3 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Medical</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.completion.store') }}">
            @csrf

            <!-- Step 1: Personal Details -->
            <div x-show="currentStep === 1" x-transition>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📋 Personal Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <x-input-label for="age" value="Age (optional)" />
                        <x-text-input id="age" type="number" name="age" x-model="formData.age" 
                                      class="block mt-1 w-full" min="1" max="150" />
                        <x-input-error :messages="$errors->get('age')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight" value="Weight (kg, optional)" />
                        <x-text-input id="weight" type="number" step="0.1" name="weight" x-model="formData.weight"
                                      class="block mt-1 w-full" min="1" max="500" />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="height" value="Height (cm, optional)" />
                        <x-text-input id="height" type="number" step="0.1" name="height" x-model="formData.height"
                                      class="block mt-1 w-full" min="1" max="300" />
                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Step 2: Emergency Contact -->
            <div x-show="currentStep === 2" x-transition>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">🚨 Emergency Contact</h3>
                
                <div class="space-y-4">
                    <div>
                        <x-input-label for="emergency_name" value="Contact Name (optional)" />
                        <x-text-input id="emergency_name" type="text" name="emergency_name" x-model="formData.emergency_name"
                                      class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('emergency_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="emergency_phone" value="Contact Phone (optional)" />
                        <x-text-input id="emergency_phone" type="tel" name="emergency_phone" x-model="formData.emergency_phone"
                                      class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('emergency_phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="emergency_relationship" value="Relationship (optional)" />
                        <x-text-input id="emergency_relationship" type="text" name="emergency_relationship" x-model="formData.emergency_relationship"
                                      class="block mt-1 w-full" placeholder="e.g., Daughter, Son, Friend" />
                        <x-input-error :messages="$errors->get('emergency_relationship')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Step 3: Medical Info -->
            <div x-show="currentStep === 3" x-transition>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">💊 Medical Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <x-input-label for="conditions" value="Medical Conditions (optional)" />
                        <textarea id="conditions" name="conditions" x-model="formData.conditions"
                                  rows="3" 
                                  class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Separate with commas: e.g., Diabetes, Hypertension"></textarea>
                        <p class="text-xs text-gray-500 mt-1">List any medical conditions</p>
                        <x-input-error :messages="$errors->get('conditions')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="medications" value="Current Medications (optional)" />
                        <textarea id="medications" name="medications" x-model="formData.medications"
                                  rows="3"
                                  class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Separate with commas: e.g., Aspirin, Metformin"></textarea>
                        <p class="text-xs text-gray-500 mt-1">List current medications</p>
                        <x-input-error :messages="$errors->get('medications')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="allergies" value="Allergies (optional)" />
                        <textarea id="allergies" name="allergies" x-model="formData.allergies"
                                  rows="2"
                                  class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Separate with commas: e.g., Penicillin, Peanuts"></textarea>
                        <p class="text-xs text-gray-500 mt-1">List any known allergies</p>
                        <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <!-- Back Button -->
                <button type="button" 
                        x-show="currentStep > 1"
                        @click="prevStep()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    ← Back
                </button>

                <!-- Skip Button -->
                <a href="{{ route('profile.completion.skip') }}"
                   x-show="currentStep < 3"
                   class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                    Skip for now
                </a>

                <div class="flex-1"></div>

                <!-- Next Button -->
                <button type="button"
                        x-show="currentStep < 3"
                        @click="nextStep()"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    Next →
                </button>

                <!-- Submit Button -->
                <button type="submit"
                        x-show="currentStep === 3"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Complete Profile ✓
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
