<div x-data="{ open: false }"
     @open-ingredient-modal.window="open = true"
     @close-ingredient-modal.window="open = false"
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    <div class="absolute inset-0 bg-black/60" @click="open = false"></div>

    <div class="relative bg-white border-2 border-black shadow-[8px_8px_0px_0px_var(--pink)] w-full max-w-md z-10"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <button @click="open = false"
                class="absolute -top-4 -right-4 w-10 h-10 rounded-full bg-black text-white flex items-center justify-center text-xl font-black border-2 border-black hover:bg-gray-800 transition-colors z-20">
            ✕
        </button>

        <div class="p-8">
            <h2 class="text-3xl font-black uppercase italic mb-6">PRODUCT TOEVOEGEN</h2>

            @if($ingredientId)
                <div class="flex items-center justify-between border-2 border-black px-4 py-3 mb-4 bg-[var(--lime)]">
                    <div>
                        <p class="text-[11px] font-black uppercase">{{ $ingredientName }}</p>
                        <p class="text-[9px] font-bold uppercase text-gray-600">{{ $ingredientCategory }}</p>
                    </div>
                    <button wire:click="clearIngredient" class="text-sm font-black hover:opacity-60 ml-4">✕</button>
                </div>
            @else
                <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">INGREDIËNT</label>
                <div class="relative mb-4">
                    <div class="flex items-center border-2 border-black px-4 py-3 bg-white">
                        <svg class="w-4 h-4 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input wire:model.live.debounce.250ms="search"
                               type="text"
                               placeholder="ZOEK EEN INGREDIËNT..."
                               class="w-full text-[11px] font-black uppercase tracking-widest placeholder-gray-400 outline-none bg-transparent">
                    </div>
                    @if(count($searchResults))
                        <div class="absolute top-full left-0 right-0 bg-white border-2 border-t-0 border-black shadow-[4px_4px_0px_0px_#000] z-30 max-h-48 overflow-y-auto">
                            @foreach($searchResults as $r)
                                <button wire:click="selectIngredient({{ $r['id'] }})"
                                        class="w-full flex items-center justify-between px-4 py-2.5 text-left hover:bg-[var(--pink-soft)] border-b border-gray-100 last:border-0 transition-colors">
                                    <span class="text-[11px] font-black uppercase">{{ $r['name'] }}</span>
                                    <span class="text-[9px] font-bold uppercase text-gray-400">{{ $r['category'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex gap-3 mb-4"
                 x-data="{
                     qty: $wire.entangle('qty'),
                     unit: $wire.entangle('unit'),
                     get step() { return ['g', 'ml'].includes(this.unit) ? 5 : 1; },
                     increment() { this.qty = String(Math.max(0, parseInt(this.qty || 0) + this.step)); },
                     decrement() { this.qty = String(Math.max(0, parseInt(this.qty || 0) - this.step)); },
                 }"
                 x-init="$watch('unit', (val) => { qty = String(['g','ml'].includes(val) ? 5 : 1); })">
                <div class="flex-1">
                    <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">HOEVEELHEID</label>
                    <div class="flex border-2 border-black">
                        <button @click="decrement()" type="button"
                                class="px-4 py-3 text-[14px] font-black border-r-2 border-black hover:bg-gray-100 transition-colors select-none">−</button>
                        <input x-model="qty"
                               type="number"
                               min="0"
                               class="flex-1 text-center text-[13px] font-black outline-none bg-transparent [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button @click="increment()" type="button"
                                class="px-4 py-3 text-[14px] font-black border-l-2 border-black hover:bg-gray-100 transition-colors select-none">+</button>
                    </div>
                </div>
                <div class="w-32">
                    <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">EENHEID</label>
                    <select x-model="unit"
                            class="w-full border-2 border-black px-3 py-3 text-[11px] font-black uppercase outline-none focus:border-brand transition-colors bg-white appearance-none cursor-pointer">
                        <option value="stuks">STUKS</option>
                        <option value="g">G</option>
                        <option value="kg">KG</option>
                        <option value="ml">ML</option>
                        <option value="l">L</option>
                        <option value="blikken">BLIKKEN</option>
                        <option value="zakjes">ZAKJES</option>
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">BEWAARPLAATS</label>
                <div class="flex gap-2">
                    <button wire:click="$set('location', 'fridge')" type="button"
                            class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75 {{ $location === 'fridge' ? 'bg-[var(--lime)] border-black text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white border-gray-300 text-gray-500' }}">
                        ❄ KOELKAST
                    </button>
                    <button wire:click="$set('location', 'freezer')" type="button"
                            class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75 {{ $location === 'freezer' ? 'bg-gray-800 border-gray-800 text-white shadow-[2px_2px_0px_0px_#000]' : 'bg-white border-gray-300 text-gray-500' }}">
                        ❄❄ VRIEZER
                    </button>
                    <button wire:click="$set('location', 'pantry')" type="button"
                            class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75 {{ $location === 'pantry' ? 'bg-[var(--yellow)] border-black text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white border-gray-300 text-gray-500' }}">
                        KAST
                    </button>
                </div>
            </div>

            <button wire:click="addItem"
                    class="w-full bg-brand text-white text-base font-black uppercase italic tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                TOEVOEGEN
            </button>
        </div>
    </div>
</div>
