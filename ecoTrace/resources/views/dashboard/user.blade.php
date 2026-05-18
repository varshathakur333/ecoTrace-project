<x-app-layout>
    <div style="max-width: 1280px; margin: 40px auto;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--near-black); padding-bottom: 16px; margin-bottom: 40px;">
            <div>
                <h1 style="font-family: var(--font-display); font-size: clamp(32px, 4vw, 48px); font-weight: 800; text-transform: uppercase;">
                    User Portal // E-waste Recycling
                </h1>
                <p style="font-family: var(--font-editorial); font-style: italic; font-size: 18px; color: var(--secondary);">
                    Welcome back, {{ auth()->user()->name }}! Let's clear out some drawer space.
                </p>
            </div>
            
            <a href="#ai-predictor" class="btn-action" style="background-color: var(--soft-lilac);">
                <span class="material-icons" style="font-size: 18px;">psychology</span>
                AI Route Optimization Active
            </a>
        </div>

        <div class="grid-layout">
            
            <!-- Left Side: Searching & Booking -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Advanced Search Filtering panel -->
                <x-card title="Search Collection Services">
                    <form action="{{ route('search') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 16px; align-items: end; flex-wrap: wrap;">
                        
                        <x-form-input 
                            name="location" 
                            label="Coverage Location" 
                            placeholder="E.g. Delhi, Sector 62..." 
                            value="{{ request('location') }}"
                            style="margin-bottom: 0;"
                        />

                        <!-- Category Select -->
                        <div style="margin-bottom: 0;">
                            <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                Category Class
                            </label>
                            <select name="category_id" style="width: 100%; border: 2px solid var(--near-black); border-radius: 6px; padding: 8px 12px; font-weight: 600; background-color: #f9f9f7;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn-action" style="background-color: var(--acid-lime); padding: 10px 16px; flex-grow: 1; justify-content: center;">
                                Filter Services
                            </button>
                            @if(request()->anyFilled(['location', 'category_id']))
                                <a href="{{ route('dashboard') }}" class="btn-action" style="background-color: var(--surface); padding: 10px 16px; text-decoration: none;">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </x-card>

                <!-- Search Listings Results -->
                <x-card title="Available Recycling Channels & Refund Services">
                    @if($services->isEmpty())
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 20px;">
                            No matching collection services found for your filters. Try search keywords like 'Delhi' or check 'All Categories'.
                        </p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($services as $service)
                                <div style="border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--surface);">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px;">
                                        <div>
                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800;">
                                                {{ $service->title }}
                                            </h4>
                                            
                                            <div style="margin-top: 4px; display: flex; gap: 10px; flex-wrap: wrap;">
                                                <span class="badge badge-verified" style="font-size: 10px; transform: none;">Verified Agency</span>
                                                <span style="font-family: var(--font-mono); font-size: 12px; font-weight: bold; color: var(--secondary);">
                                                    Category: {{ $service->category->name }}
                                                </span>
                                            </div>

                                            <p style="font-size: 14px; margin-top: 8px;">
                                                {{ $service->description }}
                                            </p>
                                            
                                            <div style="margin-top: 10px; display: flex; gap: 15px; font-size: 13px; font-weight: bold;">
                                                <span>Refund Rate: <strong style="color: var(--primary);">₹{{ $service->cost_per_kg }}/kg</strong></span>
                                                <span>Location: <strong>{{ $service->location }}</strong></span>
                                            </div>

                                            <div style="margin-top: 10px;">
                                                <strong style="font-size: 12px;">Accepted hardware types:</strong>
                                                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px;">
                                                    @foreach($service->ewaste_types as $type)
                                                        <span style="font-family: var(--font-mono); font-size: 11px; background-color: var(--pure-white); border: 1px solid var(--near-black); padding: 1px 6px; border-radius: 4px;">
                                                            {{ $type }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Booking trigger button -->
                                        <button 
                                            onclick="openBookingForm('{{ $service->id }}', '{{ $service->title }}', '₹{{ $service->cost_per_kg }}/kg')"
                                            class="btn-action" 
                                            style="background-color: var(--acid-lime);"
                                        >
                                            Schedule Pickup
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Bookings submitted -->
                <x-card title="My Collection Pickup Bookings">
                    @if($myBookings->isEmpty())
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 20px;">
                            You haven't scheduled any pickups yet. Try scheduling one above!
                        </p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($myBookings as $booking)
                                <div style="border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--pure-white);">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <span style="font-family: var(--font-mono); font-size: 12px; font-weight: bold; text-transform: uppercase; background-color: var(--soft-lilac); border: 2px solid var(--near-black); padding: 2px 6px; border-radius: 4px;">
                                                Status: {{ strtoupper($booking->status) }}
                                            </span>
                                            
                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800; margin-top: 10px;">
                                                {{ $booking->service->title }}
                                            </h4>
                                            
                                            <p style="font-size: 13px; color: var(--secondary);">
                                                Collector: <strong>{{ $booking->service->user->business_name ?? $booking->service->user->name }}</strong>
                                            </p>

                                            <p style="font-size: 14px; margin-top: 8px; line-height: 1.5;">
                                                <strong>Scheduled Pickup Date:</strong> {{ $booking->booking_date->format('Y-m-d') }}<br>
                                                <strong>Estimated Weight:</strong> {{ $booking->weight }} kg<br>
                                                <strong>Your Notes:</strong> {{ $booking->notes ?? 'None' }}
                                            </p>
                                        </div>

                                        <!-- Cancel Option if pending -->
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('user.booking.cancel', $booking->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-action" style="background-color: #ffdad6; color: #ba1a1a; padding: 6px 12px; font-size: 13px;">
                                                    Cancel Pickup
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

            </div>

            <!-- Right Side: AI Predictor, Profile and Booking Form Modal -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Booking Form (Hidden until Schedule Pickup is clicked) -->
                <div id="booking_form_panel" style="display: none;">
                    <x-card title="Schedule E-Waste pickup" style="background-color: var(--soft-lilac); border: 2px solid var(--primary);">
                        
                        <p style="font-size: 13px; font-family: var(--font-mono); margin-bottom: 12px;">
                            BOOKING SERVICE: <span id="booking_service_title" style="font-weight: bold; text-transform: uppercase;"></span> <br>
                            REFUND RATE: <span id="booking_service_rate" style="font-weight: bold; color: var(--primary);"></span>
                        </p>

                        <form action="{{ route('user.booking.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" name="service_id" id="booking_service_id">

                            <x-form-input 
                                name="booking_date" 
                                label="Schedule Date" 
                                type="date" 
                                required 
                            />

                            <x-form-input 
                                name="weight" 
                                label="Estimated Weight (kg)" 
                                type="number" 
                                step="0.1" 
                                placeholder="E.g. 5.5" 
                                required 
                            />

                            <div style="margin-bottom: 16px;">
                                <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                    Extra Pickup Notes
                                </label>
                                <textarea 
                                    name="notes" 
                                    placeholder="Enter your phone or packaging state here..."
                                    style="width: 100%; border: 2px solid var(--near-black); border-radius: 6px; padding: 8px 12px; font-family: inherit; font-size: 14px;"
                                    rows="2"
                                ></textarea>
                            </div>

                            <!-- E-waste Photo Upload -->
                            <div style="margin-bottom: 20px;">
                                <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                    Snap old tech (Photo Upload)
                                </label>
                                <input 
                                    type="file" 
                                    name="photo" 
                                    accept="image/*"
                                    style="width: 100%; border: 2px dashed var(--near-black); padding: 8px; border-radius: 6px; background-color: var(--surface);"
                                >
                                <span style="font-size: 11px; font-family: var(--font-mono); color: var(--secondary); margin-top: 4px; display: block;">
                                    Max file size 2MB. Format: jpeg, png, webp.
                                </span>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <button type="submit" class="btn-action" style="flex-grow: 1; justify-content: center; background-color: var(--primary); color: var(--pure-white);">
                                    Confirm Scheduling
                                </button>
                                <button type="button" onclick="closeBookingForm()" class="btn-action" style="background-color: var(--surface); color: var(--near-black);">
                                    Cancel
                                </button>
                            </div>

                        </form>
                    </x-card>
                </div>

                <!-- AI route optimization predictor -->
                <x-card id="ai-predictor" title="AI Route Optimization Predictor" style="background-color: var(--soft-lilac);">
                    <p style="font-size: 14px; margin-bottom: 16px; font-family: var(--font-mono);">
                        Input your sector location or city to predict optimal collection day for carbon neutrality:
                    </p>

                    <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                        <input 
                            type="text" 
                            id="ai_location_input" 
                            placeholder="E.g. Sector 62, Delhi" 
                            style="flex-grow: 1; border: 2px solid var(--near-black); border-radius: 6px; padding: 8px; font-size: 14px;"
                        >
                        <button onclick="queryAiPredictor()" class="btn-action" style="background-color: var(--acid-lime); padding: 8px 12px; font-size: 13px; box-shadow: 2px 2px 0 var(--near-black);">
                            Predict Day
                        </button>
                    </div>

                    <!-- Prediction Result Display -->
                    <div id="ai_result_box" style="display: none; border: 2px solid var(--near-black); padding: 12px; border-radius: 6px; background-color: var(--pure-white);">
                        <span style="font-family: var(--font-mono); font-size: 11px; background-color: var(--acid-lime); border: 1px solid var(--near-black); padding: 1px 6px; border-radius: 4px; font-weight: bold;">
                            AI ROUTING REPORT
                        </span>
                        
                        <p style="font-size: 14px; margin-top: 8px;">
                            <strong>Optimal Collection Day:</strong> <span id="res_day" style="color: var(--primary); font-weight: 800;"></span> <br>
                            <strong>Best Time Slot:</strong> <span id="res_slot" style="font-weight: 600;"></span> <br>
                            <strong>AI Confidence:</strong> <span id="res_conf" style="color: var(--secondary); font-weight: bold;"></span>
                        </p>
                        
                        <p id="res_explanation" style="font-size: 12px; font-style: italic; margin-top: 10px; border-top: 1px dashed var(--near-black); padding-top: 8px; line-height: 1.4;"></p>
                    </div>
                </x-card>

                <!-- Update User Profile Details -->
                <x-card title="My Profile Settings">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-form-input 
                            name="name" 
                            label="Profile Name" 
                            value="{{ auth()->user()->name }}" 
                            required 
                        />

                        <x-form-input 
                            name="phone" 
                            label="Phone Number" 
                            value="{{ auth()->user()->phone }}" 
                            required 
                        />

                        <x-form-input 
                            name="address" 
                            label="Registered Address" 
                            value="{{ auth()->user()->address }}" 
                            required 
                        />

                        <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--near-black); color: var(--pure-white);">
                            Update Profile
                        </button>
                    </form>
                </x-card>

            </div>

        </div>

    </div>

    <!-- Interactive script triggers -->
    <script>
        function openBookingForm(id, title, rate) {
            document.getElementById('booking_service_id').value = id;
            document.getElementById('booking_service_title').innerText = title;
            document.getElementById('booking_service_rate').innerText = rate;
            
            const formPanel = document.getElementById('booking_form_panel');
            formPanel.style.display = 'block';
            formPanel.scrollIntoView({ behavior: 'smooth' });
        }

        function closeBookingForm() {
            document.getElementById('booking_form_panel').style.display = 'none';
        }

        function queryAiPredictor() {
            const loc = document.getElementById('ai_location_input').value;
            if (!loc) {
                alert('Please enter a location first!');
                return;
            }

            fetch(`/api/ai/predict-day?location=${encodeURIComponent(loc)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('res_day').innerText = data.recommended_collection_day;
                        document.getElementById('res_slot').innerText = data.optimal_time_slot;
                        document.getElementById('res_conf').innerText = data.ai_confidence_percentage + '%';
                        document.getElementById('res_explanation').innerText = data.explanation;
                        
                        document.getElementById('ai_result_box').style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('AI Predictor failed:', err);
                });
        }
    </script>
</x-app-layout>
