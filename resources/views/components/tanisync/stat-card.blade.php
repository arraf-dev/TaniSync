@php
    $tones = [
        'primary' => 'bg-[#196b2c]/10 text-[#196b2c]',
        'accent' => 'bg-[#ffebde] text-[#9c5421]',
        'success' => 'bg-[#dff2df] text-[#0d8d4d]',
        'warning' => 'bg-yellow-100 text-[#d99026]',
    ];
@endphp

<div class="surface-panel h-full p-6">
    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl {{ $tones[$tone ?? 'primary'] ?? $tones['primary'] }}">
        <span class="material-symbols-outlined icon-filled text-2xl">{{ $icon }}</span>
    </div>
    <p class="text-sm font-semibold text-[#5b6658]">{{ $label }}</p>
    <p class="mt-2 font-heading text-3xl font-extrabold text-[#172018]">{{ $value }}</p>
    <p class="mt-2 text-sm text-[#5b6658]">{{ $detail }}</p>
</div>
