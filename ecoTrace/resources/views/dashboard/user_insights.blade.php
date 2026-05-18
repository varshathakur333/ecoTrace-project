<x-app-layout>
    <div style="max-width: 1280px; margin: 40px auto; padding: 0 20px;">
        
        <!-- Header Section -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 4px solid var(--near-black); padding-bottom: 20px; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
            <div>
                <span class="badge" style="background-color: var(--acid-lime); margin-bottom: 10px; font-size: 13px;">CUSTOM PORTAL // REAL-TIME METRICS</span>
                <h1 style="font-family: var(--font-display); font-size: clamp(36px, 5vw, 54px); font-weight: 800; text-transform: uppercase; line-height: 1.1; letter-spacing: -0.02em;">
                    My Green Dashboard
                </h1>
                <p style="font-family: var(--font-editorial); font-style: italic; font-size: 20px; color: var(--secondary); margin-top: 8px;">
                    Welcome back, {{ auth()->user()->name }}. Empowering a circular world, one device at a time.
                </p>
            </div>
            
            <a href="{{ route('search') }}" class="btn-action" style="background-color: var(--acid-lime);">
                <span class="material-icons">search</span>
                Find Pickups & Recycle
            </a>
        </div>

        <!-- Metric Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-bottom: 48px;">
            
            <!-- Pickups Scheduled -->
            <div style="border: 3px solid var(--near-black); background-color: var(--pure-white); padding: 24px; border-radius: 8px; box-shadow: 6px 6px 0px var(--near-black); position: relative; overflow: hidden;">
                <span style="position: absolute; right: 10px; bottom: -10px; font-size: 80px; font-family: var(--font-mono); color: rgba(0, 67, 53, 0.05); font-weight: 900; pointer-events: none;">01</span>
                <h3 style="font-family: var(--font-mono); text-transform: uppercase; font-size: 14px; color: var(--secondary); font-weight: bold;">Scheduled Pickups</h3>
                <div style="font-family: var(--font-display); font-size: 48px; font-weight: 800; margin-top: 8px;">
                    {{ $totalPickups }}
                </div>
                <p style="font-size: 13px; color: var(--near-black); margin-top: 8px; font-weight: 600;">
                    Active recycling schedules.
                </p>
            </div>

            <!-- Recycled Weight -->
            <div style="border: 3px solid var(--near-black); background-color: var(--soft-lilac); padding: 24px; border-radius: 8px; box-shadow: 6px 6px 0px var(--near-black); position: relative; overflow: hidden;">
                <span style="position: absolute; right: 10px; bottom: -10px; font-size: 80px; font-family: var(--font-mono); color: rgba(0, 0, 0, 0.05); font-weight: 900; pointer-events: none;">02</span>
                <h3 style="font-family: var(--font-mono); text-transform: uppercase; font-size: 14px; color: var(--primary); font-weight: bold;">Recycled E-Waste</h3>
                <div style="font-family: var(--font-display); font-size: 48px; font-weight: 800; margin-top: 8px;">
                    {{ number_format($totalWeight, 1) }} <span style="font-size: 24px;">kg</span>
                </div>
                <p style="font-size: 13px; color: var(--near-black); margin-top: 8px; font-weight: 600;">
                    Diverted from landfill stream.
                </p>
            </div>

            <!-- Carbon Saved -->
            <div style="border: 3px solid var(--near-black); background-color: var(--primary); color: var(--acid-lime); padding: 24px; border-radius: 8px; box-shadow: 6px 6px 0px var(--near-black); position: relative; overflow: hidden;">
                <span style="position: absolute; right: 10px; bottom: -10px; font-size: 80px; font-family: var(--font-mono); color: rgba(202, 242, 8, 0.05); font-weight: 900; pointer-events: none;">03</span>
                <h3 style="font-family: var(--font-mono); text-transform: uppercase; font-size: 14px; color: var(--acid-lime); font-weight: bold;">CO₂ Saved</h3>
                <div style="font-family: var(--font-display); font-size: 48px; font-weight: 800; margin-top: 8px;">
                    {{ number_format($carbonSaved, 2) }} <span style="font-size: 24px;">kg</span>
                </div>
                <p style="font-size: 13px; color: var(--pure-white); margin-top: 8px; font-family: var(--font-sans); font-weight: 500;">
                    Diverted carbon equivalence.
                </p>
            </div>

            <!-- Total Earnings -->
            <div style="border: 3px solid var(--near-black); background-color: var(--pure-white); padding: 24px; border-radius: 8px; box-shadow: 6px 6px 0px var(--near-black); position: relative; overflow: hidden;">
                <span style="position: absolute; right: 10px; bottom: -10px; font-size: 80px; font-family: var(--font-mono); color: rgba(0, 67, 53, 0.05); font-weight: 900; pointer-events: none;">04</span>
                <h3 style="font-family: var(--font-mono); text-transform: uppercase; font-size: 14px; color: var(--secondary); font-weight: bold;">Refund Earned</h3>
                <div style="font-family: var(--font-display); font-size: 48px; font-weight: 800; margin-top: 8px; color: var(--primary);">
                    ₹{{ number_format($totalRefund, 2) }}
                </div>
                <p style="font-size: 13px; color: var(--near-black); margin-top: 8px; font-weight: 600;">
                    Circular cash back payouts.
                </p>
            </div>

        </div>

        <!-- Main Content Area: Facts and Insights + Pickup Log -->
        <div class="grid-layout">
            
            <!-- Left Side: Interactive facts and Insights -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Interesting Interactive Facts Carousel/Grid -->
                <x-card title="Interactive E-Waste Insights & Facts">
                    <p style="font-family: var(--font-editorial); font-style: italic; font-size: 17px; margin-bottom: 24px; color: var(--secondary); border-left: 3px solid var(--near-black); padding-left: 12px;">
                        E-waste contains precious metals, hazardous toxins, and incredible recycling potential. Explore the circular economy below:
                    </p>

                    <!-- Facts Tabs -->
                    <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 20px;">
                        <button onclick="switchFact(0)" id="fact-tab-0" class="btn-action fact-tab active-tab" style="padding: 6px 12px; font-size: 12px; transform: none; box-shadow: 2px 2px 0 var(--near-black); background-color: var(--acid-lime);">
                            📱 Gold Mine
                        </button>
                        <button onclick="switchFact(1)" id="fact-tab-1" class="btn-action fact-tab" style="padding: 6px 12px; font-size: 12px; transform: none; box-shadow: 2px 2px 0 var(--near-black); background-color: var(--pure-white);">
                            💻 Laptop Save
                        </button>
                        <button onclick="switchFact(2)" id="fact-tab-2" class="btn-action fact-tab" style="padding: 6px 12px; font-size: 12px; transform: none; box-shadow: 2px 2px 0 var(--near-black); background-color: var(--pure-white);">
                            ☠️ Toxic Impact
                        </button>
                        <button onclick="switchFact(3)" id="fact-tab-3" class="btn-action fact-tab" style="padding: 6px 12px; font-size: 12px; transform: none; box-shadow: 2px 2px 0 var(--near-black); background-color: var(--pure-white);">
                            ♻️ Global Gap
                        </button>
                    </div>

                    <!-- Fact Panels -->
                    <div id="fact-panel-0" class="fact-panel" style="border: 2px solid var(--near-black); padding: 24px; border-radius: 8px; background-color: var(--surface);">
                        <h4 style="font-family: var(--font-display); font-size: 22px; font-weight: 800; margin-bottom: 12px; color: var(--primary);">
                            Phones Are Literal Gold Mines!
                        </h4>
                        <p style="font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
                            One metric ton of circuit boards contains between <strong>40 and 800 times</strong> more gold than one metric ton of mined gold ore! Recycling 1 million mobile phones yields:
                        </p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-family: var(--font-mono); font-size: 13px;">
                            <div style="background-color: var(--pure-white); padding: 10px; border: 1px solid var(--near-black); border-radius: 4px;">
                                🌟 <strong>75 lbs</strong> of Gold
                            </div>
                            <div style="background-color: var(--pure-white); padding: 10px; border: 1px solid var(--near-black); border-radius: 4px;">
                                🥈 <strong>772 lbs</strong> of Silver
                            </div>
                            <div style="background-color: var(--pure-white); padding: 10px; border: 1px solid var(--near-black); border-radius: 4px;">
                                🔌 <strong>35,274 lbs</strong> of Copper
                            </div>
                            <div style="background-color: var(--pure-white); padding: 10px; border: 1px solid var(--near-black); border-radius: 4px;">
                                🔋 <strong>33 lbs</strong> of Palladium
                            </div>
                        </div>
                    </div>

                    <div id="fact-panel-1" class="fact-panel" style="display: none; border: 2px solid var(--near-black); padding: 24px; border-radius: 8px; background-color: var(--surface);">
                        <h4 style="font-family: var(--font-display); font-size: 22px; font-weight: 800; margin-bottom: 12px; color: var(--primary);">
                            Saving Power Through Tech Circularity
                        </h4>
                        <p style="font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
                            Creating electronics requires an intense amount of fuel and resource extraction. By recycling e-waste, we divert huge amounts of carbon emissions.
                        </p>
                        <div style="background-color: var(--soft-lilac); border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; font-family: var(--font-mono); font-size: 14px;">
                            🔌 <strong>Recycling just one laptop</strong> saves the energy equivalent to the electricity used by more than 3,500 average households in a year! Let's prevent massive resource burning.
                        </div>
                    </div>

                    <div id="fact-panel-2" class="fact-panel" style="display: none; border: 2px solid var(--near-black); padding: 24px; border-radius: 8px; background-color: var(--surface);">
                        <h4 style="font-family: var(--font-display); font-size: 22px; font-weight: 800; margin-bottom: 12px; color: #ba1a1a;">
                            The Toxic Footprint of E-Waste
                        </h4>
                        <p style="font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
                            While electronics only represent around 2% of garbage in landfills, they are responsible for an overwhelming amount of toxic pollutants:
                        </p>
                        <div style="border: 2px dashed #ba1a1a; background-color: #ffdad6; color: #ba1a1a; padding: 16px; border-radius: 6px; font-weight: bold; font-family: var(--font-mono); font-size: 14px;">
                            ☠️ E-waste accounts for a massive 70% of heavy metals (lead, cadmium, mercury) in municipal landfills, contaminating water tables!
                        </div>
                    </div>

                    <div id="fact-panel-3" class="fact-panel" style="display: none; border: 2px solid var(--near-black); padding: 24px; border-radius: 8px; background-color: var(--surface);">
                        <h4 style="font-family: var(--font-display); font-size: 22px; font-weight: 800; margin-bottom: 12px; color: var(--primary);">
                            The Global Circularity Gap
                        </h4>
                        <p style="font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
                            Globally, humanity generated 62 million tonnes of e-waste in 2022. This number is set to rise by 32% by 2030.
                        </p>
                        <div style="background-color: var(--acid-lime); border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; font-family: var(--font-mono); font-size: 14px; font-weight: bold;">
                            📈 Currently, only 17.4% of e-waste is formally collected and recycled! Your bookings help push this number higher.
                        </div>
                    </div>

                    <!-- Carbon Impact Calculator Widget -->
                    <div style="margin-top: 32px; border-top: 2px solid var(--near-black); padding-top: 28px;">
                        <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">
                            🌿 Personal Carbon Calculator
                        </h4>
                        <p style="font-size: 13px; font-family: var(--font-mono); margin-bottom: 16px;">
                            Input any device weight below to estimate CO₂ emission prevention instantly:
                        </p>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <input 
                                type="number" 
                                id="calc_weight" 
                                placeholder="Device weight in kg" 
                                style="border: 2px solid var(--near-black); border-radius: 6px; padding: 8px 12px; font-size: 14px; font-family: var(--font-mono); flex-grow: 1;"
                            >
                            <button onclick="calculateCarbon()" class="btn-action" style="background-color: var(--primary); color: var(--pure-white); padding: 8px 16px; font-size: 13px; transform: none; box-shadow: 2px 2px 0 var(--near-black);">
                                Calculate CO₂
                            </button>
                        </div>
                        <div id="calc_result" style="display: none; margin-top: 16px; padding: 12px; background-color: var(--soft-lilac); border: 2px solid var(--near-black); border-radius: 6px; font-family: var(--font-mono); font-size: 14px; font-weight: bold;">
                            Result: <span id="calc_co2" style="color: var(--primary);"></span> kg of CO₂ emissions prevented!
                        </div>
                    </div>
                </x-card>

                <!-- My Pickup History -->
                <x-card title="My Pickup Requests & Status Tracker">
                    @if($myBookings->isEmpty())
                        <div style="text-align: center; padding: 30px;">
                            <p style="font-family: var(--font-editorial); font-style: italic; color: var(--secondary); font-size: 16px;">
                                No bookings scheduled yet. Divert your old cables, phones, and laptops!
                            </p>
                            <a href="{{ route('search') }}" class="btn-action" style="margin-top: 16px; background-color: var(--acid-lime);">
                                Book First Pickup
                            </a>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($myBookings as $booking)
                                <div style="border: 2px solid var(--near-black); padding: 18px; border-radius: 8px; background-color: var(--pure-white); box-shadow: 4px 4px 0 var(--near-black); transition: all 0.2s;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 12px;">
                                        <div>
                                            <div style="display: flex; gap: 8px; align-items: center;">
                                                @if($booking->status === 'completed')
                                                    <span style="font-family: var(--font-mono); font-size: 11px; background-color: var(--acid-lime); border: 1.5px solid var(--near-black); padding: 2px 8px; border-radius: 4px; font-weight: bold;">
                                                        COMPLETED
                                                    </span>
                                                @elseif($booking->status === 'cancelled')
                                                    <span style="font-family: var(--font-mono); font-size: 11px; background-color: #ffdad6; color: #ba1a1a; border: 1.5px solid var(--near-black); padding: 2px 8px; border-radius: 4px; font-weight: bold;">
                                                        CANCELLED
                                                    </span>
                                                @else
                                                    <span style="font-family: var(--font-mono); font-size: 11px; background-color: var(--soft-lilac); border: 1.5px solid var(--near-black); padding: 2px 8px; border-radius: 4px; font-weight: bold;">
                                                        PENDING APPROVAL
                                                    </span>
                                                @endif
                                                <span style="font-size: 12px; color: var(--secondary); font-family: var(--font-mono);">
                                                    ID: #EW-{{ $booking->id }}
                                                </span>
                                            </div>

                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800; margin-top: 10px;">
                                                {{ $booking->service->title }}
                                            </h4>
                                            
                                            <p style="font-size: 13px; color: var(--secondary); margin-top: 2px;">
                                                Collector: <strong>{{ $booking->service->user->business_name ?? $booking->service->user->name }}</strong>
                                            </p>

                                            <p style="font-size: 14px; margin-top: 10px; line-height: 1.5;">
                                                📅 Scheduled: <strong>{{ $booking->booking_date->format('Y-m-d') }}</strong> <br>
                                                ⚖️ Estimated Weight: <strong>{{ $booking->weight }} kg</strong>
                                            </p>

                                            @if($booking->notes)
                                                <p style="font-size: 12px; font-family: var(--font-mono); border-left: 2px solid var(--primary); padding-left: 8px; margin-top: 10px; color: var(--primary);">
                                                    Notes: "{{ $booking->notes }}"
                                                </p>
                                            @endif
                                        </div>

                                        @if($booking->status === 'pending')
                                            <form action="{{ route('user.booking.cancel', $booking->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-action" style="background-color: #ffdad6; color: #ba1a1a; padding: 6px 12px; font-size: 12px; transform: none; box-shadow: 2px 2px 0 var(--near-black);">
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

            <!-- Right Side: AI Predictor, Circular Actions & Profiles -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Circular Action Card -->
                <x-card title="Circular Actions" style="background-color: var(--primary); color: var(--pure-white);">
                    <p style="font-size: 14px; line-height: 1.5; margin-bottom: 20px;">
                        Quickly find local collection channels, register drop-offs, and receive recycling refunds:
                    </p>
                    <a href="{{ route('search') }}" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--acid-lime); color: var(--near-black);">
                        <span class="material-icons">local_shipping</span>
                        Schedule Pickup Now
                    </a>
                </x-card>

                <!-- AI route optimization predictor -->
                <x-card id="ai-predictor" title="AI Optimal Routing Day Predictor" style="background-color: var(--soft-lilac);">
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
                    <div id="ai_result_box" style="display: none; border: 2px solid var(--near-black); padding: 12px; border-radius: 6px; background-color: var(--pure-white); color: var(--near-black);">
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

                <!-- Profile settings card -->
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

    <!-- Client-side Interactive Scripts -->
    <script>
        function switchFact(index) {
            // Hide all panels
            document.querySelectorAll('.fact-panel').forEach(panel => {
                panel.style.display = 'none';
            });
            // Show selected panel
            document.getElementById(`fact-panel-${index}`).style.display = 'block';

            // Reset tab backgrounds
            document.querySelectorAll('.fact-tab').forEach(tab => {
                tab.style.backgroundColor = 'var(--pure-white)';
            });
            // Active background color
            document.getElementById(`fact-tab-${index}`).style.backgroundColor = 'var(--acid-lime)';
        }

        function calculateCarbon() {
            const weight = parseFloat(document.getElementById('calc_weight').value);
            if (isNaN(weight) || weight <= 0) {
                alert('Please enter a valid weight in kg!');
                return;
            }

            // Standard conversion: 1.44 kg CO2 prevented per kg recycled
            const co2 = (weight * 1.44).toFixed(2);
            document.getElementById('calc_co2').innerText = co2;
            document.getElementById('calc_result').style.display = 'block';
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
