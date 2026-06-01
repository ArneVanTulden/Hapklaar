<div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000] p-6 flex flex-col">

    <div class="flex items-center gap-2 mb-5">
        <h2 class="text-xl font-black uppercase italic">WIJ ZAGEN...</h2>
        <svg class="w-5 h-5 text-brand" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
        </svg>
    </div>

    {{-- Tags --}}
    @php
        $catClass = fn(?string $cat) => match ($cat) {
            'zuivel'       => 'bg-sky-200 text-sky-900 border-black',
            'groenten'     => 'bg-lime-300 text-black border-black',
            'vlees'        => 'bg-pink-500 text-white border-black',
            'vis'          => 'bg-cyan-300 text-cyan-900 border-black',
            'granen'       => 'bg-amber-300 text-black border-black',
            'kruiden'      => 'bg-lime-300 text-black border-black',
            'specerijen'   => 'bg-orange-300 text-black border-black',
            'peulvruchten' => 'bg-amber-300 text-black border-black',
            'fruit'        => 'bg-yellow-300 text-black border-black',
            'sauzen'       => 'bg-red-400 text-white border-black',
            'diepvries'    => 'bg-slate-800 text-white border-black',
            'blik'         => 'bg-gray-300 text-black border-black',
            'dranken'      => 'bg-purple-400 text-white border-black',
            default        => 'bg-gray-300 text-black border-black',
        };
    @endphp

    <div class="flex flex-wrap gap-2 mb-4 items-start content-start">
        @forelse($items as $index => $item)
                <span class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest px-2.5 py-1.5 border-2 {{ $catClass($item['category'] ?? null) }}">
                {{ $item['label'] }}
                <button wire:click="removeItem({{ $index }})"
                    class="font-black text-[14px] inline-flex items-center justify-center hover:opacity-70 transition-opacity leading-none">×</button>
            </span>
        @empty
            <p class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nog geen ingrediënten gevonden.</p>
        @endforelse
    </div>

    {{-- Flash message --}}
    @if($flash)
        <div class="mb-3 px-3 py-2 bg-[var(--lime)] border-2 border-black text-[10px] font-black uppercase tracking-widest">
            ✓ {{ $flash }}
        </div>
    @endif

    {{-- Niet herkend --}}
    <button onclick="window.dispatchEvent(new CustomEvent('open-ingredient-modal', { detail: { mode: 'scanner' } }))"
            class="w-full border-2 border-black py-3 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-[var(--pink-soft)] transition-colors mb-3">
        + NIET HERKEND?
    </button>

    {{-- Voeg toe aan inventaris --}}
    <button wire:click="addAllToInventory"
            @if(empty($items)) disabled @endif
            class="w-full py-3.5 text-[10px] font-black uppercase tracking-widest border-2 border-black shadow-[3px_3px_0px_0px_#000] transition-all duration-75
                   {{ empty($items)
                       ? 'bg-gray-100 text-gray-400 cursor-not-allowed shadow-none'
                       : 'bg-brand text-white hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000]' }}">
        VOEG TOE AAN INVENTARIS →
    </button>

</div>
