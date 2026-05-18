<div class="border-2 border-[#1a1c1b] shadow-[4px_4px_0_#1a1c1b] rounded-[6px] overflow-hidden bg-white">
    <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse']) }}>
        <thead class="bg-[#004335] text-white border-b-2 border-[#1a1c1b] text-sm uppercase font-mono">
            {{ $thead }}
        </thead>
        <tbody class="divide-y-2 divide-[#eeeeec] text-sm text-[#1a1c1b]">
            {{ $slot }}
        </tbody>
    </table>
</div>
