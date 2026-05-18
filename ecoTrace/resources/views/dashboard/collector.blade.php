<x-app-layout>
    <div style="max-width: 1280px; margin: 40px auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--near-black); padding-bottom: 16px; margin-bottom: 40px;">
            <div>
                <h1 style="font-family: var(--font-display); font-size: clamp(32px, 4vw, 48px); font-weight: 800; text-transform: uppercase;">
                    Collector Portal // listings control
                </h1>
                <p style="font-family: var(--font-editorial); font-style: italic; font-size: 18px; color: var(--secondary);">
                    Welcome, {{ auth()->user()->business_name ?? auth()->user()->name }}
                </p>
            </div>
            
            <!-- Audited Sticker Badge status -->
            @if(auth()->user()->is_verified)
                <span class="badge badge-verified" style="font-size: 16px; padding: 6px 16px;">Verified Collector Agency</span>
            @else
                <span class="badge" style="font-size: 16px; padding: 6px 16px; background-color: #ffdad6; color: #ba1a1a;">Verification Pending Audit</span>
            @endif
        </div>

        <div class="grid-layout">
            
            <!-- Left Side: Manage Client pickup requests & listings -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Client Bookings Table -->
                <x-card title="E-Waste Collection Requests / Bookings Received">
                    @if($bookings->isEmpty())
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 20px;">
                            No pickup requests submitted by customers yet.
                        </p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($bookings as $booking)
                                <div style="border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--surface);">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <span style="font-family: var(--font-mono); font-size: 12px; font-weight: bold; text-transform: uppercase; background-color: var(--soft-lilac); border: 2px solid var(--near-black); padding: 2px 6px; border-radius: 4px;">
                                                Status: {{ strtoupper($booking->status) }}
                                            </span>
                                            
                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800; margin-top: 10px;">
                                                Service: {{ $booking->service->title }}
                                            </h4>
                                            
                                            <p style="font-size: 14px; margin-top: 8px; line-height: 1.5;">
                                                <strong>Customer Name:</strong> {{ $booking->user->name }}<br>
                                                <strong>Scheduled Pickup:</strong> {{ $booking->booking_date->format('Y-m-d') }}<br>
                                                <strong>Estimated Weight:</strong> {{ $booking->weight }} kg<br>
                                                <strong>Customer Notes:</strong> {{ $booking->notes ?? 'None' }}
                                            </p>

                                            <!-- Display E-waste Photo if Uploaded -->
                                            @if($booking->photo_path)
                                                <div style="margin-top: 12px;">
                                                    <strong style="font-size: 12px; font-family: var(--font-mono);">CUSTOMER UPLOADED E-WASTE SNAPSHOT:</strong>
                                                    <div style="margin-top: 6px;">
                                                        <img src="{{ asset('storage/' . $booking->photo_path) }}" alt="Customer E-Waste Photo" style="max-width: 100%; max-height: 200px; border: 2px solid var(--near-black); border-radius: 6px; box-shadow: 2px 2px 0 var(--near-black);">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Update Status form -->
                                        <div style="border-left: 2px dashed var(--near-black); padding-left: 16px; min-width: 200px;">
                                            <form action="{{ route('collector.booking.status', $booking->id) }}" method="POST">
                                                @csrf
                                                
                                                <div style="margin-bottom: 10px;">
                                                    <label style="font-family: var(--font-mono); font-size: 11px; font-weight: bold;">UPDATE STATUS</label>
                                                    <select name="status" style="width: 100%; border: 2px solid var(--near-black); border-radius: 4px; padding: 4px; font-size: 13px;">
                                                        <option value="accepted" {{ $booking->status === 'accepted' ? 'selected' : '' }}>Accept Pickup</option>
                                                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed / Recycled</option>
                                                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancel Pickup</option>
                                                    </select>
                                                </div>

                                                <x-form-input name="notes" label="Audit Notes (Optional)" placeholder="Weight verified, cash refunded..." style="margin-bottom: 10px; font-size: 13px;" />

                                                <button type="submit" class="btn-action" style="padding: 6px 12px; font-size: 12px; width: 100%; justify-content: center; background-color: var(--near-black); color: var(--pure-white);">
                                                    Update Booking
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Active Service Postings List -->
                <x-card title="My Active E-Waste Collection Services">
                    @if($services->isEmpty())
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 20px;">
                            You haven't posted any services yet. Create one on the right side panel!
                        </p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            @foreach($services as $service)
                                <div style="border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--pure-white);">
                                    <div style="display: flex; justify-content: space-between; align-items: start;">
                                        <div>
                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800;">
                                                {{ $service->title }}
                                            </h4>
                                            <span style="font-family: var(--font-mono); font-size: 12px; color: var(--secondary);">
                                                Category: {{ $service->category->name }}
                                            </span>
                                            <p style="font-size: 14px; margin-top: 8px;">
                                                {{ $service->description }}
                                            </p>
                                            <div style="margin-top: 10px; display: flex; gap: 15px; font-size: 13px; font-weight: bold;">
                                                <span>Refund rate: <strong style="color: var(--primary);">₹{{ $service->cost_per_kg }}/kg</strong></span>
                                                <span>Location: <strong>{{ $service->location }}</strong></span>
                                            </div>
                                            <div style="margin-top: 10px;">
                                                <strong>E-Waste Accepted Types:</strong>
                                                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px;">
                                                    @foreach($service->ewaste_types as $type)
                                                        <span style="font-family: var(--font-mono); font-size: 11px; background-color: var(--soft-lilac); border: 1px solid var(--near-black); padding: 1px 6px; border-radius: 4px;">
                                                            {{ $type }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete service listing -->
                                        <form action="{{ route('collector.service.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; cursor: pointer; color: #ba1a1a; font-weight: bold; font-size: 13px; text-decoration: underline;">
                                                DELETE LISTING
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

            </div>

            <!-- Right Side: Post new collection service listing -->
            <div>
                <x-card title="Post E-Waste Service Listing">
                    
                    @if(!auth()->user()->is_verified)
                        <div style="background-color: #ffdad6; color: #ba1a1a; padding: 12px; border: 2px solid var(--near-black); border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
                            <strong>NOTICE:</strong> Since your license verification is still pending admin audit, posted services won't show up in consumer searches until approved.
                        </div>
                    @endif

                    <form action="{{ route('collector.service.store') }}" method="POST">
                        @csrf

                        <x-form-input 
                            name="title" 
                            label="Service Listing Title" 
                            placeholder="E.g. Computer Hardware and Screen Pickups" 
                            required 
                        />

                        <!-- Category select dropdown -->
                        <div style="margin-bottom: 16px;">
                            <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                Category Class
                            </label>
                            <select name="category_id" style="width: 100%; border: 2px solid var(--near-black); border-radius: 6px; padding: 8px 12px; font-weight: 600; background-color: #f9f9f7;" required>
                                @foreach(\App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                Listing Description
                            </label>
                            <textarea 
                                name="description" 
                                placeholder="Describe items you accept, pickup logistics, extra details..."
                                style="width: 100%; border: 2px solid var(--near-black); border-radius: 6px; padding: 10px; font-family: inherit; font-size: 14px;"
                                rows="3"
                            ></textarea>
                        </div>

                        <x-form-input 
                            name="location" 
                            label="Operation / Coverage Area" 
                            placeholder="E.g. Noida Sector 62, Delhi" 
                            required 
                        />

                        <x-form-input 
                            name="cost_per_kg" 
                            label="Refund Rate per Kilogram (₹)" 
                            type="number" 
                            step="0.01" 
                            placeholder="E.g. 45.00" 
                            required 
                        />

                        <!-- E-waste types checkboxes -->
                        <div style="margin-bottom: 24px;">
                            <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                Accepted E-Waste Types
                            </label>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;">
                                @foreach(['battery', 'smartphone', 'screen', 'laptop', 'cable', 'appliance'] as $type)
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                                        <input type="checkbox" name="ewaste_types[]" value="{{ $type }}" style="accent-color: var(--primary);">
                                        {{ ucfirst($type) }}
                                    </label>
                                @endforeach
                            </div>
                            @error('ewaste_types')
                                <span class="text-red-600 font-mono text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--primary); color: var(--pure-white);">
                            Publish Collection Listing
                        </button>
                    </form>

                </x-card>
            </div>

        </div>

    </div>
</x-app-layout>
