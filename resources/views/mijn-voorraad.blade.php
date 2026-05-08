<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mijn Voorraad - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-10"
              x-data="{
                  activeFilter: 'alles',
                  showAddModal: false,
                  newItem: { name: '', qty: '1', unit: 'stuks', location: 'koelkast', expiry: '' },
                  items: [
                      { id: 1,  name: 'MELK',              qty: '1',   unit: 'LITER',     location: 'koelkast',     category: 'ZUIVEL',       catClass: 'bg-sky-100 text-sky-700 border-sky-300',             expiry: '2026-05-09' },
                      { id: 2,  name: 'GRIEKSE YOGHURT',   qty: '500', unit: 'G',         location: 'koelkast',     category: 'ZUIVEL',       catClass: 'bg-sky-100 text-sky-700 border-sky-300',             expiry: '2026-05-10' },
                      { id: 3,  name: 'KIPFILET',          qty: '400', unit: 'G',         location: 'koelkast',     category: 'VLEES',        catClass: 'bg-pink-100 text-pink-700 border-pink-300',           expiry: '2026-05-09' },
                      { id: 4,  name: 'SPINAZIE',          qty: '300', unit: 'G',         location: 'koelkast',     category: 'GROENTEN',     catClass: 'bg-emerald-100 text-emerald-700 border-emerald-300',  expiry: '2026-05-12' },
                      { id: 5,  name: 'EIEREN',            qty: '6',   unit: 'STUKS',     location: 'koelkast',     category: 'ZUIVEL',       catClass: 'bg-sky-100 text-sky-700 border-sky-300',             expiry: '2026-05-20' },
                      { id: 6,  name: 'OUDE KAAS',         qty: '200', unit: 'G',         location: 'koelkast',     category: 'ZUIVEL',       catClass: 'bg-sky-100 text-sky-700 border-sky-300',             expiry: '2026-05-25' },
                      { id: 7,  name: 'RODE PAPRIKA',      qty: '2',   unit: 'STUKS',     location: 'koelkast',     category: 'GROENTEN',     catClass: 'bg-emerald-100 text-emerald-700 border-emerald-300',  expiry: '2026-05-14' },
                      { id: 8,  name: 'BOTER',             qty: '250', unit: 'G',         location: 'koelkast',     category: 'ZUIVEL',       catClass: 'bg-sky-100 text-sky-700 border-sky-300',             expiry: '2026-06-01' },
                      { id: 9,  name: 'KIPPENDIJEN',       qty: '600', unit: 'G',         location: 'vriezer',      category: 'VLEES',        catClass: 'bg-pink-100 text-pink-700 border-pink-300',           expiry: '' },
                      { id: 10, name: 'BROOD',             qty: '1',   unit: 'STUK',      location: 'vriezer',      category: 'GRANEN',       catClass: 'bg-amber-100 text-amber-700 border-amber-300',        expiry: '2026-06-15' },
                      { id: 11, name: 'DIEPVRIES ERWTEN',  qty: '400', unit: 'G',         location: 'vriezer',      category: 'GROENTEN',     catClass: 'bg-emerald-100 text-emerald-700 border-emerald-300',  expiry: '' },
                      { id: 12, name: 'TONIJNFILET',       qty: '2',   unit: 'STUKS',     location: 'vriezer',      category: 'VIS',          catClass: 'bg-cyan-100 text-cyan-700 border-cyan-300',           expiry: '' },
                      { id: 13, name: 'PENNE PASTA',       qty: '500', unit: 'G',         location: 'voorraadkast', category: 'GRANEN',       catClass: 'bg-amber-100 text-amber-700 border-amber-300',        expiry: '' },
                      { id: 14, name: 'BASMATI RIJST',     qty: '1',   unit: 'KG',        location: 'voorraadkast', category: 'GRANEN',       catClass: 'bg-amber-100 text-amber-700 border-amber-300',        expiry: '' },
                      { id: 15, name: 'TOMATENBLOKJES',    qty: '2',   unit: 'BLIKKEN',   location: 'voorraadkast', category: 'SAUZEN',       catClass: 'bg-red-100 text-red-700 border-red-300',              expiry: '2026-11-01' },
                      { id: 16, name: 'OLIJFOLIE',         qty: '750', unit: 'ML',        location: 'voorraadkast', category: 'SAUZEN',       catClass: 'bg-red-100 text-red-700 border-red-300',              expiry: '' },
                      { id: 17, name: 'KNOFLOOK',          qty: '1',   unit: 'BOL',       location: 'voorraadkast', category: 'KRUIDEN',      catClass: 'bg-lime-100 text-lime-700 border-lime-300',           expiry: '' },
                      { id: 18, name: 'RODE LINZEN',       qty: '500', unit: 'G',         location: 'voorraadkast', category: 'PEULVRUCHTEN', catClass: 'bg-orange-100 text-orange-700 border-orange-300',     expiry: '' },
                      { id: 19, name: 'SOJASAUS',          qty: '200', unit: 'ML',        location: 'voorraadkast', category: 'SAUZEN',       catClass: 'bg-red-100 text-red-700 border-red-300',              expiry: '' },
                  ],
                  get koelkastItems()   { return this.items.filter(i => i.location === 'koelkast') },
                  get vriezerItems()    { return this.items.filter(i => i.location === 'vriezer') },
                  get voorraadItems()   { return this.items.filter(i => i.location === 'voorraadkast') },
                  get expiringItems() {
                      const now = new Date(); now.setHours(0,0,0,0);
                      const soon = new Date(now); soon.setDate(soon.getDate() + 3);
                      return this.items.filter(i => {
                          if (!i.expiry) return false;
                          return new Date(i.expiry) <= soon;
                      });
                  },
                  expiryStatus(expiry) {
                      if (!expiry) return null;
                      const now = new Date(); now.setHours(0,0,0,0);
                      const soon = new Date(now); soon.setDate(soon.getDate() + 3);
                      const d = new Date(expiry);
                      if (d < now)   return 'verlopen';
                      if (d <= soon) return 'bijna';
                      return 'ok';
                  },
                  formatExpiry(expiry) {
                      if (!expiry) return '';
                      return new Date(expiry).toLocaleDateString('nl-BE', { day: '2-digit', month: '2-digit' });
                  },
                  removeItem(id) {
                      this.items = this.items.filter(i => i.id !== id);
                  },
                  addItem() {
                      if (!this.newItem.name.trim()) return;
                      this.items.push({
                          id: Date.now(),
                          name: this.newItem.name.toUpperCase(),
                          qty: this.newItem.qty,
                          unit: this.newItem.unit.toUpperCase(),
                          location: this.newItem.location,
                          category: 'OVERIG',
                          catClass: 'bg-gray-100 text-gray-700 border-gray-300',
                          expiry: this.newItem.expiry
                      });
                      this.newItem = { name: '', qty: '1', unit: 'stuks', location: 'koelkast', expiry: '' };
                      this.showAddModal = false;
                  }
              }">
            <div class="max-w-5xl mx-auto px-6">

                {{-- ============================================================
                     PAGE HEADER
                ============================================================ --}}
                <div class="relative mb-8">

                    {{-- Rotated badge --}}
                    <div class="absolute top-0 right-0 bg-[var(--yellow)] border-2 border-black px-4 py-2 text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]"
                         style="transform: rotate(2deg);">
                        BIJGEHOUDEN
                    </div>

                    {{-- Heading box --}}
                    <div class="inline-block bg-black border-b-4 border-brand pr-6 py-4 pl-4 mb-5">
                        <h1 class="text-[4rem] font-black uppercase italic leading-none text-white tracking-tight">
                            MIJN VOOR<span class="italic">RAAD</span>
                        </h1>
                    </div>

                    <p class="font-black uppercase text-sm tracking-wide leading-snug max-w-md">
                        ALLES WAT ER IN JE KOELKAST, VRIEZER EN KAST ZIT.
                    </p>
                </div>

                {{-- ============================================================
                     FILTER TABS + ADD BUTTON
                ============================================================ --}}
                <div class="flex items-center justify-between mb-6">

                    {{-- Filter pills --}}
                    <div class="flex gap-2">
                        <button @click="activeFilter = 'alles'"
                                :class="activeFilter === 'alles'
                                    ? 'bg-black text-white border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ALLES
                            <span x-text="'(' + items.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'koelkast'"
                                :class="activeFilter === 'koelkast'
                                    ? 'bg-[var(--lime)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ❄ KOELKAST
                            <span x-text="'(' + koelkastItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'vriezer'"
                                :class="activeFilter === 'vriezer'
                                    ? 'bg-gray-800 text-white border-gray-800 shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ❄❄ VRIEZER
                            <span x-text="'(' + vriezerItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'voorraadkast'"
                                :class="activeFilter === 'voorraadkast'
                                    ? 'bg-[var(--yellow)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            VOORRAADKAST
                            <span x-text="'(' + voorraadItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                    </div>

                    {{-- Add button --}}
                    <button @click="showAddModal = true"
                            class="bg-brand text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        PRODUCT TOEVOEGEN
                    </button>

                </div>

                {{-- ============================================================
                     TWO-COLUMN LAYOUT
                ============================================================ --}}
                <div class="flex gap-6 items-start">

                    {{-- ---- LEFT: Inventory sections ---- --}}
                    <div class="flex-1 min-w-0 space-y-4">

                        {{-- KOELKAST --}}
                        <div x-show="activeFilter === 'alles' || activeFilter === 'koelkast'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                                {{-- Section label --}}
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="5" y="2" width="14" height="20" rx="2"/>
                                            <path stroke-linecap="round" d="M5 10h14"/>
                                            <path stroke-linecap="round" d="M9 6v2M9 14v4"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--lime)] text-black border-black">
                                            KOELKAST
                                        </span>
                                    </div>
                                    <span x-text="koelkastItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                {{-- Items --}}
                                <div>
                                    <template x-for="item in koelkastItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group">
                                            <div class="flex items-center gap-3">
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span x-show="expiryStatus(item.expiry) === 'verlopen'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-red-500 text-white">
                                                    VERLOPEN
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'bijna'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-[var(--yellow)] text-black border border-black">
                                                    BIJNA OP
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'ok'"
                                                      x-text="formatExpiry(item.expiry)"
                                                      class="text-[9px] font-bold text-gray-400"></span>
                                                <button @click="removeItem(item.id)"
                                                        class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="koelkastItems.length === 0" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">KOELKAST IS LEEG</p>
                                </div>

                            </div>
                        </div>

                        {{-- VRIEZER --}}
                        <div x-show="activeFilter === 'alles' || activeFilter === 'vriezer'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8M18.4 5.6L5.6 18.4"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-gray-800 text-white border-gray-800">
                                            VRIEZER
                                        </span>
                                    </div>
                                    <span x-text="vriezerItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                <div>
                                    <template x-for="item in vriezerItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group">
                                            <div class="flex items-center gap-3">
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span x-show="expiryStatus(item.expiry) === 'verlopen'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-red-500 text-white">
                                                    VERLOPEN
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'bijna'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-[var(--yellow)] text-black border border-black">
                                                    BIJNA OP
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'ok'"
                                                      x-text="formatExpiry(item.expiry)"
                                                      class="text-[9px] font-bold text-gray-400"></span>
                                                <button @click="removeItem(item.id)"
                                                        class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="vriezerItems.length === 0" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VRIEZER IS LEEG</p>
                                </div>

                            </div>
                        </div>

                        {{-- VOORRAADKAST --}}
                        <div x-show="activeFilter === 'alles' || activeFilter === 'voorraadkast'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1z"/>
                                            <path stroke-linecap="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                            <path stroke-linecap="round" d="M12 12v.01"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--yellow)] text-black border-black">
                                            VOORRAADKAST
                                        </span>
                                    </div>
                                    <span x-text="voorraadItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                <div>
                                    <template x-for="item in voorraadItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group">
                                            <div class="flex items-center gap-3">
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span x-show="expiryStatus(item.expiry) === 'verlopen'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-red-500 text-white">
                                                    VERLOPEN
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'bijna'"
                                                      class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-[var(--yellow)] text-black border border-black">
                                                    BIJNA OP
                                                </span>
                                                <span x-show="expiryStatus(item.expiry) === 'ok'"
                                                      x-text="formatExpiry(item.expiry)"
                                                      class="text-[9px] font-bold text-gray-400"></span>
                                                <button @click="removeItem(item.id)"
                                                        class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="voorraadItems.length === 0" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VOORRAADKAST IS LEEG</p>
                                </div>

                            </div>
                        </div>

                        {{-- Add item row --}}
                        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                            <button @click="showAddModal = true"
                                    class="w-full flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest text-gray-500 hover:bg-[var(--pink-soft)] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" d="M12 8v8M8 12h8"/>
                                </svg>
                                PRODUCT TOEVOEGEN
                            </button>
                        </div>

                    </div>

                    {{-- ---- RIGHT: Sidebar ---- --}}
                    <div class="w-72 flex-shrink-0 space-y-4">

                        {{-- Stats box --}}
                        <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">

                            <h2 class="text-xl font-black uppercase italic text-center mb-5">JOUW KEUKEN</h2>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">TOTAAL</span>
                                    <span class="text-[12px] font-black" x-text="items.length + ' ITEMS'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄ KOELKAST</span>
                                    <span class="text-[12px] font-black" x-text="koelkastItems.length"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄❄ VRIEZER</span>
                                    <span class="text-[12px] font-black" x-text="vriezerItems.length"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">VOORRAADKAST</span>
                                    <span class="text-[12px] font-black" x-text="voorraadItems.length"></span>
                                </div>
                            </div>

                            <div class="border-t-2 border-dashed border-gray-300 mb-4"></div>

                            <div class="flex justify-between items-center mb-5">
                                <span class="text-[10px] font-black uppercase tracking-widest"
                                      :class="expiringItems.length > 0 ? 'text-red-600' : 'text-gray-500'">
                                    BIJNA VERLOPEN
                                </span>
                                <span class="text-lg font-black"
                                      :class="expiringItems.length > 0 ? 'text-red-600' : 'text-gray-800'"
                                      x-text="expiringItems.length"></span>
                            </div>

                            <button @click="showAddModal = true"
                                    class="w-full bg-brand text-white text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                + PRODUCT TOEVOEGEN
                            </button>

                        </div>

                        {{-- Expiring soon --}}
                        <div x-show="expiringItems.length > 0"
                             class="bg-[var(--yellow)] border-2 border-black shadow-[4px_4px_0px_0px_#000] p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <p class="text-[9px] font-black uppercase tracking-widest">BIJNA VERLOPEN</p>
                            </div>
                            <template x-for="item in expiringItems" :key="'exp-' + item.id">
                                <div class="flex justify-between items-center py-1.5 border-b border-black/10 last:border-0">
                                    <span x-text="item.name" class="text-[10px] font-black uppercase"></span>
                                    <span x-text="formatExpiry(item.expiry)"
                                          :class="expiryStatus(item.expiry) === 'verlopen' ? 'text-red-600' : 'text-gray-700'"
                                          class="text-[9px] font-black"></span>
                                </div>
                            </template>
                        </div>

                        {{-- Quick links --}}
                        <div class="bg-[var(--lime)] border-2 border-black shadow-[4px_4px_0px_0px_#000] p-4">
                            <p class="text-[9px] font-black uppercase tracking-widest mb-3">SNELLE LINKS</p>
                            <div class="space-y-2">
                                <a href="{{ route('mijn-keuken') }}"
                                   class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                    SCAN IJSKAST
                                    <span class="text-brand">→</span>
                                </a>
                                <a href="{{ route('boodschappen') }}"
                                   class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                    BOODSCHAPPENLIJST
                                    <span class="text-brand">→</span>
                                </a>
                                <a href="{{ route('ontdekken') }}"
                                   class="flex items-center justify-between w-full bg-white border-2 border-black px-3 py-2.5 text-[10px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                                    RECEPTEN ONTDEKKEN
                                    <span class="text-brand">→</span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ============================================================
                     BOTTOM CTA
                ============================================================ --}}
                <div class="bg-[var(--lime)] border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] flex items-center justify-between px-8 py-7 mt-6">
                    <div>
                        <p class="font-black uppercase text-lg leading-tight mb-1">WEET JE NOG WAT JE KAN MAKEN?</p>
                        <p class="text-[11px] text-gray-600 uppercase font-bold tracking-wide"
                           x-text="'JE HEBT ' + items.length + ' PRODUCTEN IN Huis. WIJ BEDENKEN HET RECEPT.'"></p>
                    </div>
                    <a href="{{ route('mijn-keuken') }}"
                       class="bg-black text-white text-[11px] font-black uppercase tracking-widest px-8 py-4 border-2 border-black hover:bg-gray-900 transition-colors flex-shrink-0 no-underline">
                        SCAN & COOK →
                    </a>
                </div>

            </div>
        </main>

        {{-- ============================================================
             PRODUCT TOEVOEGEN MODAL
        ============================================================ --}}
        <div x-show="showAddModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="showAddModal = false"
             style="display: none;">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60"></div>

            {{-- Modal card --}}
            <div class="relative bg-white border-2 border-black shadow-[8px_8px_0px_0px_var(--hot-pink)] w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                {{-- Close --}}
                <button @click="showAddModal = false"
                        class="absolute -top-4 -right-4 w-10 h-10 rounded-full bg-black text-white flex items-center justify-center text-xl font-black border-2 border-black hover:bg-gray-800 transition-colors z-20">
                    ✕
                </button>

                <div class="p-8">

                    <h2 class="text-3xl font-black uppercase italic mb-6">PRODUCT TOEVOEGEN</h2>

                    {{-- Product naam --}}
                    <div class="mb-4">
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">PRODUCT</label>
                        <input type="text"
                               x-model="newItem.name"
                               @keydown.enter="addItem()"
                               placeholder="BV. MELK, KIPFILET, PASTA..."
                               class="w-full border-2 border-black px-4 py-3 text-[11px] font-black uppercase tracking-widest placeholder-gray-300 outline-none focus:border-brand transition-colors">
                    </div>

                    {{-- Hoeveelheid + eenheid --}}
                    <div class="flex gap-3 mb-4">
                        <div class="flex-1">
                            <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">HOEVEELHEID</label>
                            <input type="number"
                                   x-model="newItem.qty"
                                   min="0"
                                   step="0.1"
                                   placeholder="1"
                                   class="w-full border-2 border-black px-4 py-3 text-[11px] font-black outline-none focus:border-brand transition-colors">
                        </div>
                        <div class="w-32">
                            <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">EENHEID</label>
                            <select x-model="newItem.unit"
                                    class="w-full border-2 border-black px-3 py-3 text-[11px] font-black uppercase outline-none focus:border-brand transition-colors bg-white appearance-none cursor-pointer">
                                <option value="stuks">STUKS</option>
                                <option value="g">G</option>
                                <option value="kg">KG</option>
                                <option value="ml">ML</option>
                                <option value="liter">LITER</option>
                                <option value="blikken">BLIKKEN</option>
                                <option value="zakjes">ZAKJES</option>
                                <option value="bol">BOL</option>
                            </select>
                        </div>
                    </div>

                    {{-- Locatie --}}
                    <div class="mb-4">
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">BEWAARPLAATS</label>
                        <div class="flex gap-2">
                            <button type="button"
                                    @click="newItem.location = 'koelkast'"
                                    :class="newItem.location === 'koelkast'
                                        ? 'bg-[var(--lime)] border-black text-black shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                ❄ KOELKAST
                            </button>
                            <button type="button"
                                    @click="newItem.location = 'vriezer'"
                                    :class="newItem.location === 'vriezer'
                                        ? 'bg-gray-800 border-gray-800 text-white shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                ❄❄ VRIEZER
                            </button>
                            <button type="button"
                                    @click="newItem.location = 'voorraadkast'"
                                    :class="newItem.location === 'voorraadkast'
                                        ? 'bg-[var(--yellow)] border-black text-black shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                KAST
                            </button>
                        </div>
                    </div>

                    {{-- THT datum --}}
                    <div class="mb-8">
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">THT DATUM <span class="text-gray-300">(OPTIONEEL)</span></label>
                        <input type="date"
                               x-model="newItem.expiry"
                               class="w-full border-2 border-black px-4 py-3 text-[11px] font-black outline-none focus:border-brand transition-colors">
                    </div>

                    {{-- Submit --}}
                    <button @click="addItem()"
                            class="w-full bg-brand text-white text-base font-black uppercase italic tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                        TOEVOEGEN
                    </button>

                </div>
            </div>
        </div>

        <x-footer />

        @livewireScripts
    </body>
</html>
