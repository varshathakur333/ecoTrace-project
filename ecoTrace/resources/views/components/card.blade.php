@props(['title' => null])
<div {{ $attributes->merge(['class' => 'border-2 border-[#1a1c1b] shadow-[4px_4px_0_#1a1c1b] bg-white rounded-[6px] p-6 hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0_#1a1c1b] transition-all duration-200']) }}>
    @if($title)
        <h3 class="font-bold text-xl mb-4 text-[#1a1c1b] border-b-2 border-[#1a1c1b] pb-2 tracking-tight uppercase">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>
