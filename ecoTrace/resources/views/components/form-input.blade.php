@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => ''])
<div class="mb-4">
    <label for="{{ $name }}" class="block font-bold text-sm text-[#1a1c1b] uppercase tracking-wide mb-1 font-mono">
        {{ $label }}
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full border-2 border-[#1a1c1b] rounded-[6px] px-4 py-2 text-[#1a1c1b] placeholder-[#3f4945] bg-[#f9f9f7] focus:outline-none focus:bg-white focus:border-[#004335] focus:ring-2 focus:ring-[#0d5c4a] transition-all']) }}
    >
    @error($name)
        <span class="text-red-600 font-mono text-xs mt-1 block font-bold">{{ $message }}</span>
    @enderror
</div>
