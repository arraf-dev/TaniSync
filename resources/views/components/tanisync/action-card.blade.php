@php
    $tones = [
        'primary' => 'bg-[#196b2c]/10 text-[#196b2c] group-hover:bg-[#196b2c] group-hover:text-white',
        'accent' => 'bg-[#ffebde] text-[#9c5421] group-hover:bg-[#9c5421] group-hover:text-white',
        'success' => 'bg-[#dff2df] text-[#0d8d4d] group-hover:bg-[#0d8d4d] group-hover:text-white',
        'neutral' => 'bg-[#f1f4ee] text-[#5b6658] group-hover:bg-[#172018] group-hover:text-white',
    ];
@endphp

<a href="{{ $href }}" class="group data-card flex items-center gap-4 transition hover:-translate-y-0.5 hover:border-[#078d45]/35">
    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl transition {{ $tones[$tone ?? 'primary'] ?? $tones['primary'] }}">
        <span class="material-symbols-outlined icon-filled text-2xl">{{ $icon }}</span>
    </div>
    <div class="min-w-0 flex-1 space-y-1">
        <p class="font-heading text-base font-extrabold text-[#061826]">{{ $title }}</p>
        <p class="text-sm leading-6 text-[#5c6f62]">{{ $description }}</p>
    </div>
    <span class="material-symbols-outlined text-lg text-[#9ca9a0] transition group-hover:translate-x-0.5 group-hover:text-[#078d45]">arrow_forward</span>
</a>
