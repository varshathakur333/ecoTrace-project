<x-app-layout>
    <div style="max-width: 1280px; margin: 40px auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--near-black); padding-bottom: 16px; margin-bottom: 40px;">
            <div>
                <h1 style="font-family: var(--font-display); font-size: clamp(32px, 4vw, 48px); font-weight: 800; text-transform: uppercase;">
                    Admin Panel // EcoTrace Control
                </h1>
                <p style="font-family: var(--font-editorial); font-style: italic; font-size: 18px; color: var(--secondary);">
                    Welcome back, {{ auth()->user()->name }}! E-waste auditing is active.
                </p>
            </div>
            <span class="badge badge-verified" style="font-size: 16px; padding: 6px 16px;">System Admin Status: Operational</span>
        </div>

        <!-- Metric Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <x-card style="background-color: var(--soft-lilac);">
                <div style="font-family: var(--font-mono); font-size: 12px; margin-bottom: 8px;">TOTAL SYSTEM COLLECTORS</div>
                <div style="font-family: var(--font-display); font-size: 36px; font-weight: 800; color: var(--primary);">{{ $collectorsCount }} Agencies</div>
            </x-card>
            
            <x-card style="background-color: var(--acid-lime);">
                <div style="font-family: var(--font-mono); font-size: 12px; margin-bottom: 8px;">PENDING AUDIT VERIFICATIONS</div>
                <div style="font-family: var(--font-display); font-size: 36px; font-weight: 800; color: var(--primary);">{{ $pendingRequests->count() }} Requests</div>
            </x-card>

            <x-card style="background-color: var(--pure-white);">
                <div style="font-family: var(--font-mono); font-size: 12px; margin-bottom: 8px;">MAPPED SERVICE CATEGORIES</div>
                <div style="font-family: var(--font-display); font-size: 36px; font-weight: 800; color: var(--primary);">{{ $categories->count() }} Classes</div>
            </x-card>
        </div>

        <div class="grid-layout">
            
            <!-- Left Side: Audits & Logs -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Pending Verifications -->
                <x-card title="Pending Collector Verifications (Query Builder)">
                    @if($pendingRequests->isEmpty())
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 20px;">
                            All registration audits completed! No pending verification requests.
                        </p>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($pendingRequests as $req)
                                <div style="border: 2px solid var(--near-black); padding: 16px; border-radius: 6px; background-color: var(--surface);">
                                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                                        <div>
                                            <h4 style="font-family: var(--font-display); font-size: 18px; font-weight: 800;">{{ $req->business_name }}</h4>
                                            <p style="font-size: 13px; font-family: var(--font-mono); margin-top: 4px;">License No: <span style="font-weight: bold;">{{ $req->license_no }}</span></p>
                                            <p style="font-size: 14px; margin-top: 8px; line-height: 1.4;">
                                                <strong>Contact:</strong> {{ $req->user->name }} ({{ $req->user->email }}) <br>
                                                <strong>Address:</strong> {{ $req->user->address }}
                                            </p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div style="display: flex; gap: 10px;">
                                            <form action="{{ route('admin.collector.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-action" style="padding: 6px 12px; font-size: 13px; background-color: var(--acid-lime);">
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <button 
                                                onclick="document.getElementById('reject-form-{{ $req->id }}').style.display = 'block'" 
                                                class="btn-action" 
                                                style="padding: 6px 12px; font-size: 13px; background-color: #ffdad6; color: #ba1a1a;"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Reject Form Modal Box inline -->
                                    <div id="reject-form-{{ $req->id }}" style="display: none; margin-top: 15px; border-top: 2px dashed var(--near-black); padding-top: 15px;">
                                        <form action="{{ route('admin.collector.reject', $req->id) }}" method="POST">
                                            @csrf
                                            <x-form-input name="notes" label="Reason for Rejection" placeholder="Enter license validation failure details..." required />
                                            <button type="submit" class="btn-action" style="background-color: var(--near-black); color: var(--pure-white); padding: 4px 10px; font-size: 12px;">
                                                Submit Rejection Notes
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- MongoDB / SQLite Logged Analytics -->
                <x-card title="System Logged Analytics Audit Trail">
                    <p style="font-size: 13px; font-family: var(--font-mono); margin-bottom: 12px; color: var(--secondary);">
                        Showing active collection search log entries (MongoDB logs interface fallback):
                    </p>
                    
                    @if(empty($analytics))
                        <p style="font-family: var(--font-editorial); font-style: italic; color: #7f8c8d; text-align: center; padding: 10px;">No search filter queries recorded yet.</p>
                    @else
                        <x-table>
                            <x-slot name="thead">
                                <tr>
                                    <th style="padding: 8px 12px;">Event Type</th>
                                    <th style="padding: 8px 12px;">Category ID</th>
                                    <th style="padding: 8px 12px;">Search Location</th>
                                    <th style="padding: 8px 12px;">User IP</th>
                                    <th style="padding: 8px 12px;">Timestamp</th>
                                </tr>
                            </x-slot>
                            @foreach($analytics as $log)
                                <tr>
                                    <td style="padding: 8px 12px; font-family: var(--font-mono); font-weight: bold; color: var(--primary);">
                                        {{ strtoupper(data_get($log, 'event_type')) }}
                                    </td>
                                    <td style="padding: 8px 12px; font-family: var(--font-mono);">
                                        {{ data_get($log, 'payload.category_id') ?? 'N/A' }}
                                    </td>
                                    <td style="padding: 8px 12px;">
                                        {{ data_get($log, 'payload.location') ?? 'All Locations' }}
                                    </td>
                                    <td style="padding: 8px 12px; font-family: var(--font-mono);">
                                        {{ data_get($log, 'payload.ip') ?? 'Local' }}
                                    </td>
                                    <td style="padding: 8px 12px; font-family: var(--font-mono); font-size: 12px;">
                                        {{ \Carbon\Carbon::parse(data_get($log, 'created_at'))->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    @endif
                </x-card>

            </div>

            <!-- Right Side: Category Administration -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                
                <!-- Create Category Form -->
                <x-card title="Add E-Waste Class Category">
                    <form action="{{ route('admin.category.store') }}" method="POST">
                        @csrf
                        
                        <x-form-input 
                            name="name" 
                            label="Category Name" 
                            placeholder="E.g. Lithium-Ion Batteries" 
                            required 
                        />

                        <div style="margin-bottom: 20px;">
                            <label class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
                                Category Description
                            </label>
                            <textarea 
                                name="description" 
                                placeholder="E.g. Rechargeable cells used in cellphones, tablets, and light accessories."
                                style="width: 100%; border: 2px solid var(--near-black); border-radius: 6px; padding: 10px; font-family: inherit; font-size: 14px;"
                                rows="3"
                            ></textarea>
                        </div>

                        <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--primary); color: var(--pure-white);">
                            Add Class Category
                        </button>
                    </form>
                </x-card>

                <!-- Existing Categories list -->
                <x-card title="Registered Categories">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($categories as $cat)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--surface); padding-bottom: 8px;">
                                <div>
                                    <strong style="font-family: var(--font-display); font-size: 15px;">{{ $cat->name }}</strong>
                                    <p style="font-size: 12px; color: var(--secondary);">Slug: {{ $cat->slug }}</p>
                                </div>
                                
                                <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; cursor: pointer; color: #ba1a1a; font-weight: bold; font-size: 12px;">
                                        DELETE
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </x-card>

            </div>

        </div>

    </div>
</x-app-layout>
