<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800 text-center">SIGN UP</h2>
        <p class="text-sm text-gray-600 text-center mt-2">Create your SilverCare account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="{ addCaregiver: false }">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Sex -->
        <div class="mt-4">
            <x-input-label for="sex" :value="__('Sex')" />
            <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select...</option>
                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Caregiver Invitation Checkbox -->
        <div class="mt-6 border-t pt-4">
            <label class="flex items-center">
                <input type="checkbox" name="add_caregiver" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" x-model="addCaregiver">
                <span class="ms-2 text-sm text-gray-600">I want to add a caregiver</span>
            </label>
        </div>

        <!-- Caregiver Details (Conditional) -->
        <div x-show="addCaregiver" x-transition class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-700 mb-3 font-semibold">Caregiver Information</p>
            
            <!-- Caregiver Name -->
            <div>
                <x-input-label for="caregiver_name" :value="__('Caregiver Full Name')" />
                <x-text-input id="caregiver_name" class="block mt-1 w-full" type="text" name="caregiver_name" :value="old('caregiver_name')" />
                <x-input-error :messages="$errors->get('caregiver_name')" class="mt-2" />
            </div>

            <!-- Caregiver Email -->
            <div class="mt-3">
                <x-input-label for="caregiver_email" :value="__('Caregiver Email')" />
                <x-text-input id="caregiver_email" class="block mt-1 w-full" type="email" name="caregiver_email" :value="old('caregiver_email')" />
                <p class="text-xs text-gray-500 mt-1">A password reset email will be sent to this address</p>
                <x-input-error :messages="$errors->get('caregiver_email')" class="mt-2" />
            </div>

            <!-- Caregiver Relationship -->
            <div class="mt-3">
                <x-input-label for="caregiver_relationship" :value="__('Relationship')" />
                <select id="caregiver_relationship" name="caregiver_relationship" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select relationship...</option>
                    <option value="Spouse" {{ old('caregiver_relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="Child" {{ old('caregiver_relationship') == 'Child' ? 'selected' : '' }}>Adult Child</option>
                    <option value="Professional Caregiver" {{ old('caregiver_relationship') == 'Professional Caregiver' ? 'selected' : '' }}>Professional Caregiver</option>
                </select>
                <x-input-error :messages="$errors->get('caregiver_relationship')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
