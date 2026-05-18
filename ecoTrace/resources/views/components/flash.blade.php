@if(session()->has('success') || session()->has('error') || session()->has('status'))
    <div {{ $attributes->merge(['class' => 'fixed bottom-5 right-5 z-50 max-w-sm border-2 border-[#1a1c1b] p-4 shadow-[4px_4px_0_#1a1c1b] rounded-[6px] transition-all duration-300 ' . (session('error') ? 'bg-[#ffdad6] text-[#ba1a1a]' : 'bg-[#caf208] text-[#1a1c1b]')]) }}>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="font-bold uppercase font-mono tracking-wider">
                    {{ session('error') ? 'Alert' : 'Success' }} //
                </span>
                <p class="text-sm font-semibold">{{ session('success') ?? session('error') ?? session('status') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-lg font-bold ml-4 hover:opacity-75">&times;</button>
        </div>
    </div>
@endif
