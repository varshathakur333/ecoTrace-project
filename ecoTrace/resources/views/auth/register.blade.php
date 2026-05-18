<x-app-layout>
    <div style="max-width: 600px; margin: 40px auto;">
        
        <x-card title="Join EcoTrace // Sign Up">

            @if($errors->any())
                <div style="background-color: #ffdad6; color: #ba1a1a; padding: 12px; border: 2px solid #1a1c1b; border-radius: 6px; margin-bottom: 20px; font-family: var(--font-mono); font-size: 13px;">
                    <strong style="text-transform: uppercase;">Validation Error:</strong>
                    <ul style="margin-top: 5px; padding-left: 15px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <x-form-input 
                    name="name" 
                    label="Full Name / Representative Name" 
                    placeholder="Enter your full name" 
                    required 
                />

                <x-form-input 
                    name="email" 
                    label="Email Address" 
                    type="email" 
                    placeholder="yourname@domain.com" 
                    required 
                />

                <x-form-input 
                    name="phone" 
                    label="Phone Number" 
                    placeholder="e.g. +91 98765 43210" 
                    required 
                />

                <x-form-input 
                    name="address" 
                    label="Pickup / Business Address" 
                    placeholder="Complete street address, city, state" 
                    required 
                />

                <!-- Account / User Type selection -->
                <div class="mb-4" style="margin-bottom: 20px;">
                    <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                        Account Role Type
                    </label>
                    <select 
                        name="role" 
                        id="role_select" 
                        onchange="toggleCollectorFields(this.value)"
                        style="width: 100%; border: 2px solid var(--near-black); rounded: 6px; padding: 8px 12px; font-weight: 600; border-radius: 6px; background-color: #f9f9f7;"
                    >
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>End-User (Household / Small Business)</option>
                        <option value="collector" {{ old('role') === 'collector' ? 'selected' : '' }}>Collection Agency / E-Waste Collector Proposer</option>
                    </select>
                </div>

                <!-- Collector specific fields (Toggled dynamically) -->
                <div id="collector_fields" style="display: {{ old('role') === 'collector' ? 'block' : 'none' }}; border: 2px dashed var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--soft-lilac); margin-bottom: 20px;">
                    <h4 style="font-family: var(--font-mono); font-weight: bold; font-size: 13px; text-transform: uppercase; margin-bottom: 12px; color: var(--primary);">
                        Collector Registration Documentation
                    </h4>
                    
                    <x-form-input 
                        name="business_name" 
                        label="Registered Business Name" 
                        placeholder="E.g. Recyclers India Ltd." 
                    />

                    <x-form-input 
                        name="license_no" 
                        label="E-Waste Recycler License / Registry Number" 
                        placeholder="E.g. MPCB/EW/2026/089" 
                    />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <x-form-input 
                        name="password" 
                        label="Password" 
                        type="password" 
                        placeholder="Min 8 chars" 
                        required 
                    />

                    <x-form-input 
                        name="password_confirmation" 
                        label="Confirm Password" 
                        type="password" 
                        placeholder="Confirm password" 
                        required 
                    />
                </div>

                <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--acid-lime); color: var(--near-black); margin-top: 10px;">
                    Create EcoTrace Account
                </button>
            </form>

            <div style="margin-top: 24px; text-align: center; font-size: 14px; border-top: 2px solid var(--near-black); padding-top: 20px;">
                Already have an account? 
                <a href="{{ route('login') }}" style="font-weight: bold; text-decoration: underline;">Log in instead</a>
            </div>

        </x-card>

    </div>

    <!-- Client-side field toggle Javascript -->
    <script>
        function toggleCollectorFields(role) {
            const fieldsDiv = document.getElementById('collector_fields');
            if (role === 'collector') {
                fieldsDiv.style.display = 'block';
            } else {
                fieldsDiv.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
