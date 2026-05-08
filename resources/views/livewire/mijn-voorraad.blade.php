<div x-data="{
    activeFilter: 'alles',
    dragId: null,
    dragOver: null,
    dragCounts: { fridge: 0, freezer: 0, pantry: 0 },
    startDrag(id, event) {
        this.dragId = id;
        this.activeFilter = 'alles';
        event.dataTransfer.effectAllowed = 'move';
        this._dragY = event.clientY;
        this._dragMoveHandler = (e) => { this._dragY = e.clientY; };
        window.addEventListener('dragover', this._dragMoveHandler);
        this._scrollLoop = setInterval(() => {
            const threshold = 120, y = this._dragY, h = window.innerHeight;
            if (y > h - threshold) window.scrollBy(0,  Math.ceil(((y - (h - threshold)) / threshold) * 16));
            else if (y < threshold) window.scrollBy(0, -Math.ceil(((threshold - y) / threshold) * 16));
        }, 16);
    },
    endDrag() {
        this.dragId = null;
        this.dragOver = null;
        this.dragCounts = { fridge: 0, freezer: 0, pantry: 0 };
        clearInterval(this._scrollLoop);
        window.removeEventListener('dragover', this._dragMoveHandler);
    },
    onDragEnter(loc) {
        this.dragCounts[loc]++;
        this.dragOver = loc;
    },
    onDragLeave(loc) {
        this.dragCounts[loc] = Math.max(0, this.dragCounts[loc] - 1);
        if (this.dragCounts[loc] === 0 && this.dragOver === loc) this.dragOver = null;
    },
    async onDrop(loc) {
        if (this.dragId !== null) {
            await $wire.moveItem(this.dragId, loc);
        }
        this.dragId = null;
        this.dragOver = null;
        this.dragCounts = { fridge: 0, freezer: 0, pantry: 0 };
    }
}">

    <main class="flex-1 py-10">
        <div class="max-w-5xl mx-auto px-6">

            {{-- PAGE HEADER --}}
            <div class="relative mb-8">
                <div class="absolute top-0 right-0 bg-[var(--yellow)] border-2 border-black px-4 py-2 text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]"
                     style="transform: rotate(2deg);">
                    BIJGEHOUDEN
                </div>
                <div class="inline-block bg-black border-b-4 border-brand pr-6 py-4 pl-4 mb-5">
                    <h1 class="text-[4rem] font-black uppercase italic leading-none text-white tracking-tight">
                        MIJN VOOR<span class="italic">RAAD</span>
                    </h1>
                </div>
                <p class="font-black uppercase text-sm tracking-wide leading-snug max-w-md">
                    ALLES WAT ER IN JE KOELKAST, VRIEZER EN KAST ZIT.
                </p>
            </div>

            {{-- FILTER TABS + ADD BUTTON --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex gap-2">
                    <button @click="activeFilter = 'alles'"
                            :class="activeFilter === 'alles'
                                ? 'bg-black text-white border-black shadow-[3px_3px_0px_0px_#000]'
                                : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                            class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                        ALLES
                        <span class="ml-1 opacity-60">({{ count($items) }})</span>
                    </button>
                    <button @click="activeFilter = 'fridge'"
                            :class="activeFilter === 'fridge'
                                ? 'bg-[var(--lime)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                            class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                        ❄ KOELKAST
                        <span class="ml-1 opacity-60">({{ count($fridgeItems) }})</span>
                    </button>
                    <button @click="activeFilter = 'freezer'"
                            :class="activeFilter === 'freezer'
                                ? 'bg-gray-800 text-white border-gray-800 shadow-[3px_3px_0px_0px_#000]'
                                : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                            class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                        ❄❄ VRIEZER
                        <span class="ml-1 opacity-60">({{ count($freezerItems) }})</span>
                    </button>
                    <button @click="activeFilter = 'pantry'"
                            :class="activeFilter === 'pantry'
                                ? 'bg-[var(--yellow)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                            class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                        VOORRAADKAST
                        <span class="ml-1 opacity-60">({{ count($pantryItems) }})</span>
                    </button>
                </div>

                <button @click="$dispatch('open-ingredient-modal')"
                        class="bg-brand text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    PRODUCT TOEVOEGEN
                </button>
            </div>

            {{-- TWO-COLUMN LAYOUT --}}
            <div class="flex gap-6 items-start">

                {{-- LEFT: Inventory sections --}}
                <div class="flex-1 min-w-0 space-y-4">

                    {{-- KOELKAST --}}
                    <div x-show="activeFilter === 'alles' || activeFilter === 'fridge'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-white border-2 p-5 transition-all duration-150"
                             :class="dragOver === 'fridge' && dragId !== null
                                 ? 'border-brand shadow-[4px_4px_0px_0px_var(--brand)]'
                                 : 'border-black shadow-[4px_4px_0px_0px_#000]'"
                             @dragover.prevent
                             @dragenter.prevent="onDragEnter('fridge')"
                             @dragleave="onDragLeave('fridge')"
                             @drop.prevent="onDrop('fridge')">

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="5" y="2" width="14" height="20" rx="2"/>
                                        <path stroke-linecap="round" d="M5 10h14M9 6v2M9 14v4"/>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--lime)] text-black border-black">KOELKAST</span>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ count($fridgeItems) }} ITEMS</span>
                            </div>

                            <div>
                                @foreach($fridgeItems as $item)
                                    <div wire:key="fridge-{{ $item['id'] }}"
                                         class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                         draggable="true"
                                         @dragstart="startDrag({{ $item['id'] }}, $event)"
                                         @dragend="endDrag()"
                                         :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                            </svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 border {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                            <div>
                                                <p class="text-[11px] font-black uppercase">{{ $item['name'] }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                            </div>
                                        </div>
                                        <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            @if(count($fridgeItems) === 0)
                                <div x-show="dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">KOELKAST IS LEEG</p>
                                </div>
                            @endif

                            <div x-show="dragId !== null"
                                 class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100"
                                 :class="dragOver === 'fridge' ? 'border-brand text-brand' : 'border-gray-200 text-gray-300'">
                                ❄ HIER NEERZETTEN
                            </div>
                        </div>
                    </div>

                    {{-- VRIEZER --}}
                    <div x-show="activeFilter === 'alles' || activeFilter === 'freezer'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-white border-2 p-5 transition-all duration-150"
                             :class="dragOver === 'freezer' && dragId !== null
                                 ? 'border-brand shadow-[4px_4px_0px_0px_var(--brand)]'
                                 : 'border-black shadow-[4px_4px_0px_0px_#000]'"
                             @dragover.prevent
                             @dragenter.prevent="onDragEnter('freezer')"
                             @dragleave="onDragLeave('freezer')"
                             @drop.prevent="onDrop('freezer')">

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8M18.4 5.6L5.6 18.4"/>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-gray-800 text-white border-gray-800">VRIEZER</span>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ count($freezerItems) }} ITEMS</span>
                            </div>

                            <div>
                                @foreach($freezerItems as $item)
                                    <div wire:key="freezer-{{ $item['id'] }}"
                                         class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                         draggable="true"
                                         @dragstart="startDrag({{ $item['id'] }}, $event)"
                                         @dragend="endDrag()"
                                         :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                            </svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 border {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                            <div>
                                                <p class="text-[11px] font-black uppercase">{{ $item['name'] }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                            </div>
                                        </div>
                                        <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            @if(count($freezerItems) === 0)
                                <div x-show="dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VRIEZER IS LEEG</p>
                                </div>
                            @endif

                            <div x-show="dragId !== null"
                                 class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100"
                                 :class="dragOver === 'freezer' ? 'border-brand text-brand' : 'border-gray-200 text-gray-300'">
                                ❄❄ HIER NEERZETTEN
                            </div>
                        </div>
                    </div>

                    {{-- VOORRAADKAST --}}
                    <div x-show="activeFilter === 'alles' || activeFilter === 'pantry'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-white border-2 p-5 transition-all duration-150"
                             :class="dragOver === 'pantry' && dragId !== null
                                 ? 'border-brand shadow-[4px_4px_0px_0px_var(--brand)]'
                                 : 'border-black shadow-[4px_4px_0px_0px_#000]'"
                             @dragover.prevent
                             @dragenter.prevent="onDragEnter('pantry')"
                             @dragleave="onDragLeave('pantry')"
                             @drop.prevent="onDrop('pantry')">

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1z"/>
                                        <path stroke-linecap="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M12 12v.01"/>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--yellow)] text-black border-black">VOORRAADKAST</span>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ count($pantryItems) }} ITEMS</span>
                            </div>

                            <div>
                                @foreach($pantryItems as $item)
                                    <div wire:key="pantry-{{ $item['id'] }}"
                                         class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                         draggable="true"
                                         @dragstart="startDrag({{ $item['id'] }}, $event)"
                                         @dragend="endDrag()"
                                         :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                            </svg>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 border {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                            <div>
                                                <p class="text-[11px] font-black uppercase">{{ $item['name'] }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                            </div>
                                        </div>
                                        <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            @if(count($pantryItems) === 0)
                                <div x-show="dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VOORRAADKAST IS LEEG</p>
                                </div>
                            @endif

                            <div x-show="dragId !== null"
                                 class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100"
                                 :class="dragOver === 'pantry' ? 'border-brand text-brand' : 'border-gray-200 text-gray-300'">
                                HIER NEERZETTEN
                            </div>
                        </div>
                    </div>

                    {{-- Add item row --}}
                    <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                        <button @click="$dispatch('open-ingredient-modal')"
                                class="w-full flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest text-gray-500 hover:bg-[var(--pink-soft)] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" d="M12 8v8M8 12h8"/>
                            </svg>
                            PRODUCT TOEVOEGEN
                        </button>
                    </div>

                </div>

                {{-- RIGHT: Sidebar --}}
                <div class="w-72 flex-shrink-0 space-y-4">

                    <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">
                        <h2 class="text-xl font-black uppercase italic text-center mb-5">JOUW KEUKEN</h2>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">TOTAAL</span>
                                <span class="text-[12px] font-black">{{ count($items) }} ITEMS</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄ KOELKAST</span>
                                <span class="text-[12px] font-black">{{ count($fridgeItems) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄❄ VRIEZER</span>
                                <span class="text-[12px] font-black">{{ count($freezerItems) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">VOORRAADKAST</span>
                                <span class="text-[12px] font-black">{{ count($pantryItems) }}</span>
                            </div>
                        </div>
                        <button @click="$dispatch('open-ingredient-modal')"
                                class="w-full bg-brand text-white text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                            + PRODUCT TOEVOEGEN
                        </button>
                    </div>

                    <div class="bg-[var(--lime)] border-2 border-black shadow-[4px_4px_0px_0px_#000] p-4">
                        <p class="text-[9px] font-black uppercase tracking-widest mb-3">SNELLE LINKS</p>
                        <div class="space-y-2">
                            <a href="{{ route('mijn-keuken') }}"
                               class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                SCAN IJSKAST <span class="text-brand">→</span>
                            </a>
                            <a href="{{ route('boodschappen') }}"
                               class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                BOODSCHAPPENLIJST <span class="text-brand">→</span>
                            </a>
                            <a href="{{ route('ontdekken') }}"
                               class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                RECEPTEN ONTDEKKEN <span class="text-brand">→</span>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            {{-- BOTTOM CTA --}}
            <div class="bg-[var(--lime)] border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] flex items-center justify-between px-8 py-7 mt-6">
                <div>
                    <p class="font-black uppercase text-lg leading-tight mb-1">WEET JE NOG WAT JE KAN MAKEN?</p>
                    <p class="text-[11px] text-gray-600 uppercase font-bold tracking-wide">
                        JE HEBT {{ count($items) }} PRODUCTEN IN HUIS. WIJ BEDENKEN HET RECEPT.
                    </p>
                </div>
                <a href="{{ route('mijn-keuken') }}"
                   class="bg-black text-white text-[11px] font-black uppercase tracking-widest px-8 py-4 border-2 border-black hover:bg-gray-900 transition-colors flex-shrink-0 no-underline">
                    SCAN & COOK →
                </a>
            </div>

        </div>
    </main>

    <livewire:ingredient-modal />

</div>
