<div>
    <main class="flex-1 py-6 md:py-10">
        <div class="max-w-5xl mx-auto px-4 md:px-6">

            {{-- PAGE HEADER --}}
            <div class="relative mb-8">
                <div class="absolute top-0 right-0 bg-[var(--lime)] border-2 border-black px-3 py-1.5 md:px-4 md:py-2 text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]"
                     style="transform: rotate(-2deg);">
                    GEEN STRESS
                </div>
                <div class="inline-block bg-black border-b-4 border-brand pr-6 py-4 pl-4 mb-5">
                    <h1 class="text-3xl sm:text-5xl md:text-[4rem] font-black uppercase italic leading-none text-white tracking-tight">
                        BOODSC<span class="italic">HAPPEN</span>
                    </h1>
                </div>
                <p class="font-black uppercase text-xs md:text-sm tracking-wide leading-snug max-w-md">
                    ALLES WAT JE NODIG HEBT VOOR EEN LEGENDARISCHE WEEK.
                </p>
            </div>

            {{-- TWO-COLUMN LAYOUT --}}
            <div class="flex flex-col lg:flex-row gap-6 lg:items-start">

                {{-- LEFT: Shopping list --}}
                <div class="flex-1 min-w-0 space-y-4">

                    @forelse($sections as $section)
                        <div class="relative bg-[#fffef9] border-2 border-black shadow-[6px_6px_0px_0px_#000] p-6"
                             style="background: repeating-linear-gradient(180deg, #fffef9 0px, #fffef9 26px, #eef2f7 26px, #eef2f7 27px);">
                            <div class="absolute -top-3 left-10 h-6 w-24 bg-[var(--yellow)]/80 border-2 border-black"
                                 style="transform: rotate(-2deg);"></div>
                            <div class="absolute inset-y-0 left-7 w-px bg-red-400/60"></div>
                            <div class="absolute left-2 top-7 flex flex-col gap-3">
                                <span class="w-2 h-2 rounded-full border border-black/40 bg-white"></span>
                                <span class="w-2 h-2 rounded-full border border-black/40 bg-white"></span>
                                <span class="w-2 h-2 rounded-full border border-black/40 bg-white"></span>
                            </div>

                            <div class="inline-block mb-4 ml-4">
                                <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 {{ $section['label_cls'] }}">
                                    {{ $section['label'] }}
                                </span>
                            </div>

                            <div class="space-y-0 ml-4">
                                @foreach($section['items'] as $i => $item)
                                    <div class="flex items-center justify-between py-2.5 group {{ $i < count($section['items']) - 1 ? 'border-b border-blue-100' : '' }}">
                                        <div class="flex items-center gap-3 flex-1 cursor-pointer" wire:click="toggleItem({{ $item->id }})">
                                            <span class="w-5 h-5 border-2 border-black flex items-center justify-center flex-shrink-0 {{ $item->is_checked ? 'bg-[var(--lime)]' : 'bg-white group-hover:bg-[var(--pink-soft)]' }} transition-colors">
                                                @if($item->is_checked)
                                                    <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M2 6l3 3 5-5"/></svg>
                                                @endif
                                            </span>
                                            <span class="text-[12px] font-bold tracking-wide {{ $item->is_checked ? 'line-through text-gray-400' : '' }}">
                                                @if($item->quantity){{ $item->quantity }} {{ $item->unit }} @endif{{ $item->name }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($item->price_estimate)
                                                <span class="text-[12px] font-black {{ $item->is_checked ? 'text-gray-400' : '' }}">
                                                    €{{ number_format($item->price_estimate, 2, ',', '.') }}
                                                </span>
                                            @endif
                                            <button wire:click="removeItem({{ $item->id }})"
                                                    class="text-gray-300 hover:text-red-500 transition-colors text-sm font-black leading-none">✕</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @empty
                        <div class="bg-[#fffef9] border-2 border-black shadow-[6px_6px_0px_0px_#000] p-10 text-center"
                             style="background: repeating-linear-gradient(180deg, #fffef9 0px, #fffef9 26px, #eef2f7 26px, #eef2f7 27px);">
                            <p class="text-[11px] font-black uppercase tracking-widest text-gray-400">LIJST IS LEEG. VOEG EEN ITEM TOE.</p>
                        </div>
                    @endforelse

                    {{-- Add item button --}}
                    <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                        <button x-data @click="$dispatch('open-ingredient-modal', { mode: 'shopping' })"
                                class="w-full flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest text-gray-500 hover:bg-[var(--pink-soft)] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" d="M12 8v8M8 12h8"/>
                            </svg>
                            ITEM TOEVOEGEN
                        </button>
                    </div>

                </div>

                {{-- RIGHT: Kassa sidebar --}}
                <div class="w-full lg:w-72 flex-shrink-0 space-y-4">

                    <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">

                        <h2 class="text-xl font-black uppercase italic text-center mb-5">KASSA MOMENTJE</h2>

                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">ITEMS</span>
                            <span class="text-[12px] font-black">{{ $totalItems }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">AFGEVINKT</span>
                            <span class="text-[12px] font-black">{{ $checkedItems }} / {{ $totalItems }}</span>
                        </div>

                        @if($checkedItems > 0)
                            <button wire:click="moveCheckedToInventory"
                                    class="w-full bg-[var(--lime)] text-black text-[10px] font-black uppercase tracking-widest py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 mb-2">
                                NAAR INVENTARIS ({{ $checkedItems }})
                            </button>
                            <button wire:click="removeCheckedItems"
                                    class="w-full bg-red-500 text-white text-[10px] font-black uppercase tracking-widest py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 mb-3">
                                VERWIJDER AANGEVINKTE ({{ $checkedItems }})
                            </button>
                        @endif

                        @if($totalPrice > 0)
                            <div class="border-t-2 border-dashed border-gray-300 mb-3"></div>
                            <div class="flex justify-between items-center mb-5">
                                <span class="text-base font-black uppercase">TOTAAL</span>
                                <span class="text-2xl font-black">€{{ number_format($totalPrice, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="space-y-2.5">
                            <button class="w-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                BEKIJK BIJ ALBERT HE<span class="italic">IJ</span>N
                            </button>
                            <button class="w-full bg-[var(--yellow)] text-black text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                BEKIJK BIJ COLRUYT
                            </button>
                        </div>

                        <p class="text-center text-[8px] font-bold uppercase tracking-widest text-gray-400 mt-4">
                            BEDANKT VOOR HET KOKEN BIJ HAPKLAAR
                        </p>

                    </div>

                    <div class="bg-[var(--lime)] border-2 border-black shadow-[4px_4px_0px_0px_#000] p-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest mb-1">BUDGET HACK</p>
                                <p class="text-[11px] font-bold leading-snug">{!! \App\Support\Settings::get('budget_hack_text', 'Wissel kip voor kikkererwten en bespaar €2,40!') !!}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <livewire:ingredient-modal />
</div>
