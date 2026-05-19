<div class="mb-8">

    @if($source === 'inventory' && ! $visible)
        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000]">
            <button wire:click="showInventoryMatches"
                    class="w-full flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest text-gray-700 hover:bg-[var(--pink-soft)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 17a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>
                WAT KAN IK MAKEN?
            </button>
        </div>
    @else
        <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6 flex-wrap">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black uppercase">JOUW MATCHES</h2>
            @if(! empty($matches))
                <span class="bg-[var(--lime)] border-2 border-black px-3 py-1 text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]">JACKPOT!</span>
            @endif
            @if($source === 'inventory')
                <button wire:click="hideInventoryMatches"
                        class="ml-auto text-[10px] font-black uppercase tracking-widest px-3 py-1.5 bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                    VERBERG
                </button>
            @endif
        </div>

        @if(empty($matches))
            <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000] px-5 sm:px-8 py-8 sm:py-10 text-center">
                @if($source === 'inventory')
                    <p class="font-black uppercase text-sm sm:text-base mb-1">GEEN MATCHES</p>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-gray-500">VOEG INGREDIENTEN TOE AAN JE VOORRAAD.</p>
                @else
                    <p class="font-black uppercase text-sm sm:text-base mb-1">NOG NIKS GESCAND</p>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-gray-500">SCAN JE KOELKAST OM PASSENDE RECEPTEN TE ZIEN.</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($matches as $i => $match)
                    @php
                        $recipe  = $match['recipe'];
                        $pct     = $match['pct'];
                        $missing = $match['missing'];
                        $shadow  = match($i) {
                            0       => 'shadow-[5px_5px_0px_0px_var(--hot-pink)]',
                            2       => 'shadow-[5px_5px_0px_0px_var(--lime)]',
                            default => 'shadow-[5px_5px_0px_0px_#7C3AED]',
                        };
                        $imgSrc  = $recipe->image_path
                            ? asset('storage/' . $recipe->image_path)
                            : asset('images/kater_bowl.png');
                    @endphp
                    <div class="bg-white border-2 border-black {{ $shadow }} flex flex-col">

                        <div class="relative overflow-hidden border-b-2 border-black" style="aspect-ratio:4/3;">
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $recipe->title }}"
                                 class="w-full h-full object-cover">

                            <div class="absolute top-3 right-3 w-12 h-12 rounded-full bg-[var(--lime)] border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                                <span class="text-[11px] font-black leading-none">{{ $pct }}%</span>
                            </div>

                            @if($recipe->category)
                                <span class="absolute bottom-3 left-3 text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-brand text-white">
                                    {{ strtoupper($recipe->category->name) }}
                                </span>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-sm leading-snug mb-2">{{ strtoupper($recipe->title) }}</h3>

                            <div class="flex items-center gap-3 mb-3">
                                @if($recipe->prep_time_minutes)
                                    <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
                                        </svg>
                                        {{ $recipe->prep_time_minutes }} MIN
                                    </span>
                                @endif
                                @if($recipe->calories_per_portion)
                                    <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        {{ (int) $recipe->calories_per_portion }} KCAL
                                    </span>
                                @endif
                            </div>

                            @if(! empty($missing))
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach(array_slice($missing, 0, 4) as $miss)
                                        <span class="flex items-center gap-1 text-[8px] font-black uppercase tracking-widest px-2 py-1 border border-black text-gray-600">
                                            <span class="text-brand font-black">✕</span> MIST: {{ $miss }}
                                        </span>
                                    @endforeach
                                    @if(count($missing) > 4)
                                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 text-gray-500">
                                            +{{ count($missing) - 4 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('recept', $recipe->id) }}"
                               class="mt-auto w-full bg-brand text-white text-[10px] font-black uppercase tracking-widest py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 text-center">
                                BEKIJK RECEPT →
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
