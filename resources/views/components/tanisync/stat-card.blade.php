@php
    $tones = [
        'primary' => 'bg-[#196b2c]/10 text-[#196b2c]',
        'accent' => 'bg-[#ffebde] text-[#9c5421]',
        'success' => 'bg-[#dff2df] text-[#0d8d4d]',
        'warning' => 'bg-yellow-100 text-[#d99026]',
    ];
@endphp

<div class="data-card h-full">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-[#718174]">{{ $label }}</p>
            <p class="mt-2 font-heading text-2xl font-extrabold text-[#061826] md:text-3xl">{{ $value }}</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $tones[$tone ?? 'primary'] ?? $tones['primary'] }}">
            <span class="material-symbols-outlined icon-filled text-2xl">{{ $icon }}</span>
        </div>
    </div>
    <p class="mt-4 border-t border-[#eef4ed] pt-3 text-sm leading-6 text-[#5c6f62]">{{ $detail }}</p>
</div>
