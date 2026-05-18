<x-app-layout>
    <div style="margin: 40px auto; max-width: 1200px;">
        
        <!-- Big Swiss Brutalist Hero Section -->
        <div style="display: grid; grid-template-columns: 7fr 5fr; gap: 40px; align-items: center; margin-bottom: 80px;">
            <div>
                <h1 style="font-family: var(--font-display); font-size: clamp(48px, 6vw, 84px); line-height: 0.95; font-weight: 800; text-transform: uppercase; letter-spacing: -0.04em; color: var(--primary); margin-bottom: 24px;">
                    {{ __('welcome_title') }}
                </h1>
                <p style="font-family: var(--font-editorial); font-style: italic; font-size: 24px; color: var(--near-black); line-height: 1.4; margin-bottom: 32px;">
                    {{ __('welcome_subtitle') }}
                </p>
                <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn-action">
                        {{ __('collect_ewaste') }}
                        <span class="material-icons">arrow_forward</span>
                    </a>
                    <span class="badge badge-verified" style="font-size: 14px; padding: 6px 12px;">Active in Delhi, NCR & Mumbai</span>
                </div>
            </div>
            <div>
                <!-- Simulated AI / Impact Sticker Card -->
                <x-card style="background-color: var(--soft-lilac); transform: rotate(1deg);">
                    <div style="font-family: var(--font-mono); font-size: 14px; margin-bottom: 12px;">VERIFIED STATISTICS // 2026</div>
                    <div style="font-family: var(--font-display); font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 8px; color: var(--primary);">482,901 KG</div>
                    <div style="font-weight: 600; text-transform: uppercase; font-size: 14px; margin-bottom: 24px;">Kilograms of e-waste safely diverted from landfills.</div>
                    
                    <div style="border-top: 2px solid var(--near-black); padding-top: 16px;">
                        <span style="font-family: var(--font-mono); font-size: 12px; background: var(--acid-lime); padding: 2px 6px; border: 2px solid var(--near-black); border-radius: 4px;">AI COMPLIANCE CHECK</span>
                        <p style="font-size: 13px; font-weight: 500; margin-top: 8px; font-family: var(--font-mono);">Optimized pickup routes divert ~12.4% more carbon emissions daily.</p>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- How it works Section -->
        <h2 style="font-family: var(--font-display); font-size: 48px; font-weight: 800; text-transform: uppercase; letter-spacing: -0.02em; border-bottom: 4px solid var(--near-black); padding-bottom: 12px; margin-bottom: 40px;">
            {{ __('how_it_works') }}
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 80px;">
            <x-card title="01 // Digitize">
                <h3 style="font-family: var(--font-display); font-size: 20px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">{{ __('digitize_drawer') }}</h3>
                <p style="font-size: 15px; color: var(--near-black);">{{ __('digitize_drawer_desc') }}</p>
            </x-card>

            <x-card title="02 // Pick Up">
                <h3 style="font-family: var(--font-display); font-size: 20px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">{{ __('free_pickup') }}</h3>
                <p style="font-size: 15px; color: var(--near-black);">{{ __('free_pickup_desc') }}</p>
            </x-card>

            <x-card title="03 // Earn">
                <h3 style="font-family: var(--font-display); font-size: 20px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">{{ __('earn_credits') }}</h3>
                <p style="font-size: 15px; color: var(--near-black);">{{ __('earn_credits_desc') }}</p>
            </x-card>
        </div>

        <!-- Call to Action Banner -->
        <x-card style="background-color: var(--primary); color: var(--pure-white); text-align: center; padding: 60px 40px; transform: rotate(-0.5deg);">
            <h2 style="font-family: var(--font-display); font-size: 36px; font-weight: 800; text-transform: uppercase; color: var(--acid-lime); margin-bottom: 16px;">
                {{ __('stop_scrolling') }}
            </h2>
            <p style="font-size: 18px; max-width: 600px; margin: 0 auto 32px; font-family: var(--font-editorial); font-style: italic; color: #eeeeec;">
                Ready to clear out your desk and receive instant carbon offsets?
            </p>
            <a href="{{ route('register') }}" class="btn-action" style="background-color: var(--acid-lime); color: var(--near-black);">
                Get Started Now
            </a>
        </x-card>

    </div>
</x-app-layout>
