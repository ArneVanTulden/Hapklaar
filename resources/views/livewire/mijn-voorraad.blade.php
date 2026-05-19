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
    },
    startTouchDrag(id, event) {
        this.dragId = id;
        this.activeFilter = 'alles';
        const updateOver = (clientX, clientY) => {
            const el = document.elementFromPoint(clientX, clientY);
            const zone = el && el.closest ? el.closest('[data-droploc]') : null;
            this.dragOver = zone ? zone.dataset.droploc : null;
        };
        this._touchMove = (e) => {
            e.preventDefault();
            const t = e.touches[0];
            if (!t) return;
            updateOver(t.clientX, t.clientY);
            const h = window.innerHeight, threshold = 100;
            if (t.clientY > h - threshold) window.scrollBy(0, 10);
            else if (t.clientY < threshold) window.scrollBy(0, -10);
        };
        this._touchEnd = async () => {
            const target = this.dragOver;
            const id = this.dragId;
            window.removeEventListener('touchmove', this._touchMove);
            window.removeEventListener('touchend', this._touchEnd);
            window.removeEventListener('touchcancel', this._touchEnd);
            this.dragId = null;
            this.dragOver = null;
            if (id !== null && target) {
                await $wire.moveItem(id, target);
            }
        };
        window.addEventListener('touchmove', this._touchMove, { passive: false });
        window.addEventListener('touchend', this._touchEnd);
        window.addEventListener('touchcancel', this._touchEnd);
    }
}">

    <main class="flex-1 py-6 md:py-10">
        <div class="max-w-5xl mx-auto px-4 md:px-6">

            {{-- PAGE HEADER --}}
            <div class="relative mb-8">
                <div class="inline-block bg-black border-b-4 border-brand pr-6 py-4 pl-4 mb-5">
                    <h1 class="text-3xl sm:text-5xl md:text-[4rem] font-black uppercase italic leading-none text-white tracking-tight">
                        MIJN VOOR<span class="italic">RAAD</span>
                    </h1>
                </div>
                <p class="font-black uppercase text-xs md:text-sm tracking-wide leading-snug max-w-md">
                    ALLES WAT ER IN JE KOELKAST, VRIEZER EN KAST ZIT.
                </p>
            </div>

            {{-- FILTER TABS + ADD BUTTON --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex gap-2 flex-wrap">
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
                        KOELKAST
                        <span class="ml-1 opacity-60">({{ count($fridgeItems) }})</span>
                    </button>
                    <button @click="activeFilter = 'freezer'"
                            :class="activeFilter === 'freezer'
                                ? 'bg-gray-800 text-white border-gray-800 shadow-[3px_3px_0px_0px_#000]'
                                : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                            class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                        VRIEZER
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

            </div>

            {{-- TWO-COLUMN LAYOUT --}}
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-stretch lg:items-start">

                {{-- LEFT: Fridge + Pantry visuals --}}
                <div class="flex-1 min-w-0 space-y-6">

                    {{-- ══════════════════════════════════════════════ --}}
                    {{-- KOELKAST + VRIEZER — stainless steel fridge   --}}
                    {{-- ══════════════════════════════════════════════ --}}
                    <div x-show="activeFilter === 'alles' || activeFilter === 'fridge' || activeFilter === 'freezer'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="relative">

                        {{-- Fridge outer casing — white body --}}
                        <div class="overflow-hidden border-[5px] border-black"
                             style="border-radius: 28px 28px 6px 6px; box-shadow: 14px 14px 0 0 #000; background: linear-gradient(175deg, #fafaf8 0%, #f0f0ee 60%, #e6e6e4 100%);">

                            {{-- Top edge cap (depth illusion) --}}
                            <div class="h-3 border-b border-black/15"
                                 style="background: linear-gradient(180deg, #ffffff 0%, #e8e8e6 100%);"></div>

                            {{-- Digital control panel --}}
                            <div class="flex items-center justify-between px-5 py-2 border-b-[3px] border-black"
                                 style="background: linear-gradient(180deg, #0d0d0d 0%, #050505 100%);">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2 h-2 rounded-full bg-[var(--lime)]"
                                         style="box-shadow: 0 0 8px #b6f000, 0 0 18px rgba(182,240,0,0.4);"></div>
                                    <span class="text-[7px] font-black uppercase tracking-[0.3em] text-white/50">POWER</span>
                                </div>
                                <div class="px-2.5 py-0.5 border border-[var(--lime)]/30 font-mono text-[var(--lime)] text-[9px] font-black"
                                     style="background: rgba(0,0,0,0.7); letter-spacing: 0.1em; box-shadow: inset 0 0 4px rgba(182,240,0,0.2);">
                                    4°C  ❄ −18°C
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-4 h-4 rounded-full border border-white/15 flex items-center justify-center" style="background: rgba(255,255,255,0.04);">
                                        <div class="w-1 h-1 rounded-full bg-white/40"></div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border border-white/15 flex items-center justify-center" style="background: rgba(255,255,255,0.04);">
                                        <span class="text-[7px] text-white/40">+</span>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border border-white/15 flex items-center justify-center" style="background: rgba(255,255,255,0.04);">
                                        <span class="text-[7px] text-white/40">−</span>
                                    </div>
                                </div>
                            </div>

                            {{-- ╔══════════════════════════════════╗ --}}
                            {{-- ║   FRIDGE DOOR — magnets, notes  ║ --}}
                            {{-- ╚══════════════════════════════════╝ --}}
                            <div x-show="activeFilter === 'alles' || activeFilter === 'fridge'"
                                 class="relative px-5 pt-4 pb-2 flex items-center gap-2.5 flex-wrap"
                                 style="background: linear-gradient(175deg, #f5f5f3 0%, #ebebe9 100%);">

                                {{-- Magnet 1: Italia souvenir --}}
                                <div class="border-2 border-black flex flex-col items-center justify-center font-black relative overflow-hidden"
                                     style="width: 40px; height: 32px; transform: rotate(-4deg); box-shadow: 2px 2px 0 0 #000, inset -1px -1px 3px rgba(0,0,0,0.3), inset 1px 1px 2px rgba(255,255,255,0.4); border-radius: 3px;">
                                    <div class="absolute inset-0 flex">
                                        <div class="flex-1" style="background: #009246;"></div>
                                        <div class="flex-1" style="background: #f5f5f0;"></div>
                                        <div class="flex-1" style="background: #ce2b37;"></div>
                                    </div>
                                    <span class="relative text-[6px] uppercase tracking-wider text-black bg-white/85 px-1 rounded-sm" style="text-shadow: 0 1px 0 rgba(255,255,255,0.6);">ITALIA</span>
                                </div>

                                {{-- Magnet 2: hot-pink heart --}}
                                <div class="border-2 border-black flex items-center justify-center font-black"
                                     style="width: 30px; height: 30px; background: var(--hot-pink); transform: rotate(6deg); box-shadow: 2px 2px 0 0 #000; border-radius: 50%;">
                                    <span class="text-[12px] text-white">♥</span>
                                </div>

                                {{-- Hapklaar logo sticker --}}
                                <img src="{{ asset('images/Hapklaar-LOGO.png') }}" alt="Hapklaar" class="h-8 w-auto block mx-4" style="transform: rotate(-2deg) scale(2); transform-origin: center;">

                                {{-- Photo polaroid --}}
                                <div class="border-2 border-black p-1 pb-2 mx-2"
                                     style="background: #fafafa; transform: rotate(3deg) scale(1.2); transform-origin: center; box-shadow: 2px 2px 0 0 #000;">
                                    <img src="{{ asset('images/koelkastfoto.jpg') }}" alt="" class="w-7 h-5 object-cover block">
                                </div>

                            </div>

                            {{-- KOELKAST compartment (interior visible through door opening) --}}
                                <div x-show="activeFilter === 'alles' || activeFilter === 'fridge'"
                                     data-droploc="fridge"
                                     class="relative transition-all duration-150 mx-5 mb-4 overflow-hidden border-[4px] border-gray-800"
                                     :class="dragOver === 'fridge' && dragId !== null ? 'ring-2 ring-inset ring-brand/40' : ''"
                                     style="border-radius: 4px; background: linear-gradient(150deg, #e0f3fa 0%, #cce8f4 50%, #b8dcee 100%); box-shadow: inset 0 4px 12px rgba(0,0,0,0.35), inset 0 -2px 8px rgba(0,0,0,0.2);"
                                     @dragover.prevent
                                     @dragenter.prevent="onDragEnter('fridge')"
                                     @dragleave="onDragLeave('fridge')"
                                     @drop.prevent="onDrop('fridge')">

                                    {{-- Interior LED light strip --}}
                                    <div class="h-2"
                                         style="background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.85) 15%, rgba(255,255,255,0.98) 50%, rgba(255,255,255,0.85) 85%, transparent 100%); box-shadow: 0 3px 12px rgba(220,240,255,0.8);"></div>

                                    <div class="p-4 pt-3">
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="text-[9px] font-black uppercase tracking-widest bg-sky-300 text-black border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_#000]">KOELKAST</span>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-blue-600/50">{{ count($fridgeItems) }} ITEMS</span>
                                        </div>

                                        @foreach($fridgeItems as $item)
                                            <div wire:key="fridge-{{ $item['id'] }}"
                                                 class="group transition-opacity duration-100"
                                                 draggable="true"
                                                 @dragstart="startDrag({{ $item['id'] }}, $event)"
                                                 @dragend="endDrag()"
                                                 :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                                <div class="flex items-center justify-between py-2.5 px-2"
                                                     style="background: rgba(255,255,255,0.3); border-radius: 3px;">
                                                    <div class="flex items-center gap-3">
                                                        <span @touchstart.prevent="startTouchDrag({{ $item['id'] }}, $event)" style="touch-action: none;" class="p-2 -m-1 flex-shrink-0 cursor-grab">
                                                            <svg class="w-3 h-4 text-blue-200 group-hover:text-blue-400" viewBox="0 0 8 14" fill="currentColor">
                                                                <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                                <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                                <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                            </svg>
                                                        </span>
                                                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border-2 border-black shadow-[1px_1px_0px_0px_#000] {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                                        <div>
                                                            <p class="text-[12px] font-black uppercase">{{ $item['name'] }}</p>
                                                            <p class="text-[9px] text-blue-600/50 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                                        </div>
                                                    </div>
                                                    @php $step = in_array($item['unit'], ['G', 'ML']) ? 50 : 1; @endphp
                                                    <div class="flex items-center gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                                        <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ -$step }})"
                                                                class="w-6 h-6 border border-blue-200 bg-white/80 text-blue-500 hover:bg-white hover:border-gray-400 hover:text-black transition-colors text-xs font-black flex items-center justify-center">−</button>
                                                        <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ $step }})"
                                                                class="w-6 h-6 border border-blue-200 bg-white/80 text-blue-500 hover:bg-white hover:border-gray-400 hover:text-black transition-colors text-xs font-black flex items-center justify-center">+</button>
                                                        <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                                class="w-6 h-6 border border-blue-200 bg-white/80 text-blue-500 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center">✕</button>
                                                    </div>
                                                </div>
                                                {{-- Glass shelf bar --}}
                                                @if(!$loop->last)
                                                    <div style="height: 5px; margin: 3px 0; background: linear-gradient(180deg, rgba(255,255,255,0.95) 0%, rgba(190,225,245,0.8) 40%, rgba(150,200,230,0.5) 100%); box-shadow: 0 3px 8px rgba(0,60,120,0.2), inset 0 1px 0 rgba(255,255,255,0.9);"></div>
                                                @endif
                                            </div>
                                        @endforeach

                                        @if(count($fridgeItems) === 0)
                                            <div x-show="dragId === null" class="py-10 text-center">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-300/60">KOELKAST IS LEEG</p>
                                            </div>
                                        @endif

                                        <div x-show="dragId !== null"
                                             class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100 bg-white/20"
                                             :class="dragOver === 'fridge' ? 'border-brand text-brand' : 'border-blue-300/50 text-blue-300/50'">
                                            HIER NEERZETTEN
                                        </div>
                                    </div>
                                </div>


                            {{-- ╔══════════════════════════════════╗ --}}
                            {{-- ║   FREEZER DRAWER (pull-out)     ║ --}}
                            {{-- ╚══════════════════════════════════╝ --}}
                            <div x-show="activeFilter === 'alles' || activeFilter === 'freezer'"
                                 class="border-t-[5px] border-black"
                                 style="background: linear-gradient(180deg, #d8d8d6 0%, #c8c8c6 100%);">

                                {{-- Drawer pull-handle bar --}}
                                <div class="px-4 py-2 flex items-center justify-center border-b-[3px] border-black"
                                     style="background: linear-gradient(180deg, #ececea 0%, #d8d8d6 100%);">
                                    <div class="w-full h-3 border-2 border-black flex items-center justify-center"
                                         style="border-radius: 3px; background: linear-gradient(180deg, #5a5a5a 0%, #888 30%, #d4d4d4 50%, #888 70%, #5a5a5a 100%); box-shadow: inset 0 1px 0 rgba(255,255,255,0.4), 0 2px 0 #000;">
                                        <div class="flex gap-3">
                                            <div class="w-1 h-1 rounded-full bg-black/40"></div>
                                            <div class="w-1 h-1 rounded-full bg-black/40"></div>
                                            <div class="w-1 h-1 rounded-full bg-black/40"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Drawer body / freezer content --}}
                                <div data-droploc="freezer"
                                     class="relative transition-all duration-150 mx-4 my-3 overflow-hidden border-[4px] border-gray-800"
                                     :class="dragOver === 'freezer' && dragId !== null ? 'ring-2 ring-inset ring-brand/40' : ''"
                                     style="border-radius: 4px; background: linear-gradient(160deg, #8fc8e0 0%, #6eb0ce 40%, #58a0c0 100%); box-shadow: inset 0 4px 12px rgba(0,0,0,0.35), inset 0 -2px 8px rgba(0,0,0,0.2);"
                                     @dragover.prevent
                                     @dragenter.prevent="onDragEnter('freezer')"
                                     @dragleave="onDragLeave('freezer')"
                                     @drop.prevent="onDrop('freezer')">

                                    {{-- Frost overlay --}}
                                    <div class="absolute inset-0 pointer-events-none"
                                         style="background: radial-gradient(ellipse at 12% 25%, rgba(255,255,255,0.22) 0%, transparent 45%), radial-gradient(ellipse at 88% 75%, rgba(255,255,255,0.18) 0%, transparent 40%), radial-gradient(ellipse at 50% 10%, rgba(255,255,255,0.14) 0%, transparent 30%);"></div>

                                    <div class="p-4 relative" style="z-index: 1;">
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="text-[9px] font-black uppercase tracking-widest bg-blue-900 text-white border-2 border-black px-2 py-0.5 shadow-[2px_2px_0px_0px_rgba(0,50,80,0.6)]">❄ VRIEZER</span>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-white/45">{{ count($freezerItems) }} ITEMS</span>
                                        </div>

                                        @foreach($freezerItems as $item)
                                            <div wire:key="freezer-{{ $item['id'] }}"
                                                 class="group transition-opacity duration-100"
                                                 draggable="true"
                                                 @dragstart="startDrag({{ $item['id'] }}, $event)"
                                                 @dragend="endDrag()"
                                                 :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                                <div class="flex items-center justify-between py-2.5 px-2"
                                                     style="background: rgba(255,255,255,0.22); border-radius: 3px;">
                                                    <div class="flex items-center gap-3">
                                                        <span @touchstart.prevent="startTouchDrag({{ $item['id'] }}, $event)" style="touch-action: none;" class="p-2 -m-1 flex-shrink-0 cursor-grab">
                                                            <svg class="w-3 h-4 text-white/35 group-hover:text-white/60" viewBox="0 0 8 14" fill="currentColor">
                                                                <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                                <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                                <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                            </svg>
                                                        </span>
                                                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border-2 border-black shadow-[1px_1px_0px_0px_#000] {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                                        <div>
                                                            <p class="text-[12px] font-black uppercase text-white/90">{{ $item['name'] }}</p>
                                                            <p class="text-[9px] text-white/45 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                                        </div>
                                                    </div>
                                                    @php $step = in_array($item['unit'], ['G', 'ML']) ? 50 : 1; @endphp
                                                    <div class="flex items-center gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                                        <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ -$step }})"
                                                                class="w-6 h-6 border border-white/35 bg-white/20 text-white/70 hover:bg-white/40 hover:border-white/60 hover:text-white transition-colors text-xs font-black flex items-center justify-center">−</button>
                                                        <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ $step }})"
                                                                class="w-6 h-6 border border-white/35 bg-white/20 text-white/70 hover:bg-white/40 hover:border-white/60 hover:text-white transition-colors text-xs font-black flex items-center justify-center">+</button>
                                                        <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                                class="w-6 h-6 border border-white/35 bg-white/20 text-white/70 hover:bg-red-400/40 hover:border-red-300/60 hover:text-red-100 transition-colors text-xs font-black flex items-center justify-center">✕</button>
                                                    </div>
                                                </div>
                                                @if(!$loop->last)
                                                    <div style="height: 4px; margin: 3px 0; background: linear-gradient(180deg, rgba(255,255,255,0.55) 0%, rgba(160,210,240,0.35) 100%); box-shadow: 0 2px 6px rgba(0,40,80,0.25);"></div>
                                                @endif
                                            </div>
                                        @endforeach

                                        @if(count($freezerItems) === 0)
                                            <div x-show="dragId === null" class="py-10 text-center">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-white/35">VRIEZER IS LEEG</p>
                                            </div>
                                        @endif

                                        <div x-show="dragId !== null"
                                             class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100 bg-white/10"
                                             :class="dragOver === 'freezer' ? 'border-brand text-brand' : 'border-white/30 text-white/30'">
                                            HIER NEERZETTEN
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Bottom kickplate (the dark strip at the very bottom of fridges) --}}
                            <div class="border-t-[3px] border-black h-3.5 flex items-center px-4 gap-1"
                                 style="background: linear-gradient(180deg, #1a1a1a 0%, #050505 100%);">
                                @for($v = 0; $v < 18; $v++)
                                    <div class="flex-1 h-0.5 rounded-full" style="background: rgba(255,255,255,0.06);"></div>
                                @endfor
                            </div>

                            {{-- Adjustable feet --}}
                            <div class="flex justify-between px-6 pb-1.5 pt-1">
                                <div class="w-9 h-2.5 border-2 border-t-0 border-black"
                                     style="background: linear-gradient(180deg, #777 0%, #555 100%); border-radius: 0 0 5px 5px;"></div>
                                <div class="w-9 h-2.5 border-2 border-t-0 border-black"
                                     style="background: linear-gradient(180deg, #777 0%, #555 100%); border-radius: 0 0 5px 5px;"></div>
                            </div>

                        </div>{{-- end fridge body --}}

                        {{-- ═══════════════════════════════════════════ --}}
                        {{-- HARDWARE — hinges, handle, etc. (siblings)  --}}
                        {{-- ═══════════════════════════════════════════ --}}

                        {{-- Door hinges (left side, two of them) --}}
                        <div class="absolute border-[2px] border-black"
                             style="left: -4px; top: 12%; width: 14px; height: 18px; border-radius: 3px; background: linear-gradient(90deg, #555 0%, #aaa 50%, #888 100%); box-shadow: 2px 2px 0 0 #000; z-index: 30;">
                            <div class="absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); width: 4px; height: 4px; border-radius: 50%; background: #2a2a2a; box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);"></div>
                        </div>
                        <div class="absolute border-[2px] border-black"
                             style="left: -4px; top: 38%; width: 14px; height: 18px; border-radius: 3px; background: linear-gradient(90deg, #555 0%, #aaa 50%, #888 100%); box-shadow: 2px 2px 0 0 #000; z-index: 30;">
                            <div class="absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); width: 4px; height: 4px; border-radius: 50%; background: #2a2a2a; box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);"></div>
                        </div>

                        {{-- BIG chrome handle — fridge signature --}}
                        {{-- Top mount bracket --}}
                        <div class="absolute border-[3px] border-black"
                             style="right: 8px; top: 11%; width: 18px; height: 16px; background: linear-gradient(180deg, #5a5a5a 0%, #888 50%, #444 100%); box-shadow: 3px 3px 0 0 #000; z-index: 25;"></div>
                        {{-- Bottom mount bracket --}}
                        <div class="absolute border-[3px] border-black"
                             style="right: 8px; top: calc(11% + 130px); width: 18px; height: 16px; background: linear-gradient(180deg, #5a5a5a 0%, #888 50%, #444 100%); box-shadow: 3px 3px 0 0 #000; z-index: 25;"></div>
                        {{-- Handle bar --}}
                        <div class="absolute border-[3px] border-black"
                             style="right: -10px; top: calc(11% + 12px); height: 130px; width: 28px; border-radius: 14px; background: linear-gradient(90deg, #1a1a1a 0%, #4a4a4a 8%, #909090 22%, #d8d8d8 38%, #ffffff 50%, #ececec 62%, #a0a0a0 78%, #4a4a4a 92%, #1a1a1a 100%); box-shadow: 6px 6px 0 0 #000; z-index: 20;">
                            <div class="absolute inset-x-2" style="top: 15%; height: 1.5px; background: rgba(0,0,0,0.3);"></div>
                            <div class="absolute inset-x-2" style="top: 32%; height: 1.5px; background: rgba(0,0,0,0.3);"></div>
                            <div class="absolute inset-x-2" style="top: 49%; height: 1.5px; background: rgba(0,0,0,0.3);"></div>
                            <div class="absolute inset-x-2" style="top: 66%; height: 1.5px; background: rgba(0,0,0,0.3);"></div>
                            <div class="absolute inset-x-2" style="top: 83%; height: 1.5px; background: rgba(0,0,0,0.3);"></div>
                        </div>

                        {{-- Freezer drawer pull bracket (right side, lower) --}}
                        <div class="absolute border-[2px] border-black"
                             style="right: -12px; bottom: 14%; width: 16px; height: 22px; border-radius: 3px; background: linear-gradient(90deg, #444 0%, #888 50%, #5a5a5a 100%); box-shadow: 4px 4px 0 0 #000;"></div>

                    </div>{{-- end fridge wrapper --}}

                    {{-- ═══════════════════════════════════════ --}}
                    {{-- VOORRAADKAST — double-door cabinet     --}}
                    {{-- ═══════════════════════════════════════ --}}
                    <div x-show="activeFilter === 'alles' || activeFilter === 'pantry'"
                        class="mt-14"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        <div data-droploc="pantry"
                             class="overflow-hidden border-[5px] border-black transition-all duration-150"
                             :class="dragOver === 'pantry' && dragId !== null
                                 ? 'border-brand shadow-[10px_10px_0px_0px_var(--brand)]'
                                 : 'border-black shadow-[10px_10px_0px_0px_#000]'"
                             style="background: linear-gradient(180deg, #b07c00 0%, #9a6c00 100%);"
                             @dragover.prevent
                             @dragenter.prevent="onDragEnter('pantry')"
                             @dragleave="onDragLeave('pantry')"
                             @drop.prevent="onDrop('pantry')">

                            {{-- Cabinet crown --}}
                            <div class="flex items-center justify-between px-5 py-3 border-b-[4px] border-black"
                                 style="background: linear-gradient(180deg, #8a5e00 0%, #7a5000 100%);">
                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1">
                                        <div class="w-2 h-2 rounded-full border border-black/50" style="background: radial-gradient(circle at 35% 35%, #c8940a, #8a6000);"></div>
                                        <div class="w-2 h-2 rounded-full border border-black/50" style="background: radial-gradient(circle at 35% 35%, #c8940a, #8a6000);"></div>
                                    </div>
                                    <span class="text-[11px] font-black uppercase tracking-widest text-[#ffd060]">VOORRAADKAST</span>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-[#ffd060]/45">{{ count($pantryItems) }} ITEMS</span>
                            </div>

                            {{-- Two cabinet door fronts (decorative) --}}
                            <div class="flex border-b-[4px] border-black">
                                <div class="flex-1 border-r-[3px] border-black p-2">
                                    <div class="border-2 border-black/25 h-7 flex items-center justify-end pr-2"
                                         style="background: rgba(255,255,255,0.08); border-radius: 2px; box-shadow: inset 1px 1px 0 rgba(255,255,255,0.12), inset -1px -1px 0 rgba(0,0,0,0.2);">
                                        <div class="w-3.5 h-3.5 rounded-full border-2 border-black/60"
                                             style="background: radial-gradient(circle at 35% 35%, #e8b820, #9a7200); box-shadow: 1px 1px 0 rgba(0,0,0,0.4);"></div>
                                    </div>
                                </div>
                                <div class="flex-1 p-2">
                                    <div class="border-2 border-black/25 h-7 flex items-center justify-start pl-2"
                                         style="background: rgba(255,255,255,0.08); border-radius: 2px; box-shadow: inset 1px 1px 0 rgba(255,255,255,0.12), inset -1px -1px 0 rgba(0,0,0,0.2);">
                                        <div class="w-3.5 h-3.5 rounded-full border-2 border-black/60"
                                             style="background: radial-gradient(circle at 35% 35%, #e8b820, #9a7200); box-shadow: 1px 1px 0 rgba(0,0,0,0.4);"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Cabinet interior --}}
                            <div class="p-5 bg-[#fffce8]">
                                <div>
                                    @foreach($pantryItems as $item)
                                        <div wire:key="pantry-{{ $item['id'] }}"
                                             class="flex items-center justify-between py-3 group transition-opacity duration-100 border-b-2 last:border-b-0"
                                             style="border-bottom-color: rgba(160,110,0,0.18);"
                                             draggable="true"
                                             @dragstart="startDrag({{ $item['id'] }}, $event)"
                                             @dragend="endDrag()"
                                             :class="dragId === {{ $item['id'] }} ? 'opacity-30' : 'opacity-100'">
                                            <div class="flex items-center gap-3">
                                                <span @touchstart.prevent="startTouchDrag({{ $item['id'] }}, $event)" style="touch-action: none;" class="p-2 -m-1 flex-shrink-0 cursor-grab">
                                                    <svg class="w-3 h-4 text-yellow-600/50 group-hover:text-yellow-700" viewBox="0 0 8 14" fill="currentColor">
                                                        <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                        <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                        <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                    </svg>
                                                </span>
                                                <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border-2 border-black shadow-[1px_1px_0px_0px_#000] {{ $item['catClass'] }}">{{ $item['category'] }}</span>
                                                <div>
                                                    <p class="text-[12px] font-black uppercase">{{ $item['name'] }}</p>
                                                    <p class="text-[9px] text-yellow-800/55 font-bold uppercase">{{ $item['qty'] }} {{ $item['unit'] }}</p>
                                                </div>
                                            </div>
                                            @php $step = in_array($item['unit'], ['G', 'ML']) ? 50 : 1; @endphp
                                            <div class="flex items-center gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                                <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ -$step }})"
                                                        class="w-6 h-6 border border-yellow-500 bg-white/80 text-yellow-700 hover:bg-white hover:border-gray-400 hover:text-black transition-colors text-xs font-black flex items-center justify-center">−</button>
                                                <button wire:click.stop="updateQuantity({{ $item['id'] }}, {{ $step }})"
                                                        class="w-6 h-6 border border-yellow-500 bg-white/80 text-yellow-700 hover:bg-white hover:border-gray-400 hover:text-black transition-colors text-xs font-black flex items-center justify-center">+</button>
                                                <button wire:click.stop="removeItem({{ $item['id'] }})"
                                                        class="w-6 h-6 border border-yellow-500 bg-white/80 text-yellow-700 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center">✕</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if(count($pantryItems) === 0)
                                    <div x-show="dragId === null" class="py-8 text-center">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-yellow-700/45">KAST IS LEEG</p>
                                    </div>
                                @endif

                                <div x-show="dragId !== null"
                                     class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100"
                                     :class="dragOver === 'pantry' ? 'border-brand text-brand' : 'border-yellow-500 text-yellow-600'">
                                    HIER NEERZETTEN
                                </div>
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
                <div class="w-full lg:w-72 flex-shrink-0 space-y-4">

                    <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">
                        <h2 class="text-xl font-black uppercase italic text-center mb-5">JOUW KEUKEN</h2>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">TOTAAL</span>
                                <span class="text-[12px] font-black">{{ count($items) }} ITEMS</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">KOELKAST</span>
                                <span class="text-[12px] font-black">{{ count($fridgeItems) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">VRIEZER</span>
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
            <div class="bg-[var(--lime)] border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6 px-5 md:px-8 py-5 md:py-7 mt-6">
                <div>
                    <p class="font-black uppercase text-base md:text-lg leading-tight mb-1">WEET JE NOG WAT JE KAN MAKEN?</p>
                    <p class="text-[11px] text-gray-600 uppercase font-bold tracking-wide">
                        JE HEBT {{ count($items) }} PRODUCTEN IN HUIS. WIJ BEDENKEN HET RECEPT.
                    </p>
                </div>
                <a href="{{ route('mijn-keuken') }}"
                   class="bg-black text-white text-[11px] font-black uppercase tracking-widest text-center px-8 py-4 border-2 border-black hover:bg-gray-900 transition-colors flex-shrink-0 no-underline">
                    SCAN & COOK →
                </a>
            </div>

        </div>
    </main>

    <livewire:ingredient-modal />

</div>
