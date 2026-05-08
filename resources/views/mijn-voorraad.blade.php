<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Mijn Voorraad - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <script>window.__inventoryItems = @json($items);</script>
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-10" x-data="inventaris()">
            <div class="max-w-5xl mx-auto px-6">

                {{-- ============================================================
                     PAGE HEADER
                ============================================================ --}}
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

                {{-- ============================================================
                     FILTER TABS + ADD BUTTON
                ============================================================ --}}
                <div class="flex items-center justify-between mb-6">

                    <div class="flex gap-2">
                        <button @click="activeFilter = 'alles'"
                                :class="activeFilter === 'alles'
                                    ? 'bg-black text-white border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ALLES
                            <span x-text="'(' + items.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'fridge'"
                                :class="activeFilter === 'fridge'
                                    ? 'bg-[var(--lime)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ❄ KOELKAST
                            <span x-text="'(' + fridgeItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'freezer'"
                                :class="activeFilter === 'freezer'
                                    ? 'bg-gray-800 text-white border-gray-800 shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            ❄❄ VRIEZER
                            <span x-text="'(' + freezerItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                        <button @click="activeFilter = 'pantry'"
                                :class="activeFilter === 'pantry'
                                    ? 'bg-[var(--yellow)] text-black border-black shadow-[3px_3px_0px_0px_#000]'
                                    : 'bg-white text-black border-black hover:bg-[var(--pink-soft)]'"
                                class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 transition-all duration-75">
                            VOORRAADKAST
                            <span x-text="'(' + pantryItems.length + ')'" class="ml-1 opacity-60"></span>
                        </button>
                    </div>

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
                                            <path stroke-linecap="round" d="M5 10h14"/>
                                            <path stroke-linecap="round" d="M9 6v2M9 14v4"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--lime)] text-black border-black">
                                            KOELKAST
                                        </span>
                                    </div>
                                    <span x-text="fridgeItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                <div>
                                    <template x-for="item in fridgeItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                             draggable="true"
                                             @dragstart="startDrag(item.id, $event)"
                                             @dragend="endDrag()"
                                             :class="dragId === item.id ? 'opacity-30' : 'opacity-100'">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                    <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                    <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                    <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                </svg>
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <button @click.stop="removeItem(item.id)"
                                                    class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="fridgeItems.length === 0 && dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">KOELKAST IS LEEG</p>
                                </div>

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
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-gray-800 text-white border-gray-800">
                                            VRIEZER
                                        </span>
                                    </div>
                                    <span x-text="freezerItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                <div>
                                    <template x-for="item in freezerItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                             draggable="true"
                                             @dragstart="startDrag(item.id, $event)"
                                             @dragend="endDrag()"
                                             :class="dragId === item.id ? 'opacity-30' : 'opacity-100'">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                    <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                    <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                    <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                </svg>
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <button @click.stop="removeItem(item.id)"
                                                    class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="freezerItems.length === 0 && dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VRIEZER IS LEEG</p>
                                </div>

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
                                            <path stroke-linecap="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                            <path stroke-linecap="round" d="M12 12v.01"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 bg-[var(--yellow)] text-black border-black">
                                            VOORRAADKAST
                                        </span>
                                    </div>
                                    <span x-text="pantryItems.length + ' ITEMS'" class="text-[9px] font-black uppercase tracking-widest text-gray-400"></span>
                                </div>

                                <div>
                                    <template x-for="item in pantryItems" :key="item.id">
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group transition-opacity duration-100"
                                             draggable="true"
                                             @dragstart="startDrag(item.id, $event)"
                                             @dragend="endDrag()"
                                             :class="dragId === item.id ? 'opacity-30' : 'opacity-100'">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-3 h-4 text-gray-300 group-hover:text-gray-400 flex-shrink-0 cursor-grab" viewBox="0 0 8 14" fill="currentColor">
                                                    <circle cx="2" cy="2" r="1.5"/><circle cx="6" cy="2" r="1.5"/>
                                                    <circle cx="2" cy="7" r="1.5"/><circle cx="6" cy="7" r="1.5"/>
                                                    <circle cx="2" cy="12" r="1.5"/><circle cx="6" cy="12" r="1.5"/>
                                                </svg>
                                                <span x-text="item.category"
                                                      :class="['text-[8px] font-black uppercase tracking-widest px-2 py-1 border', item.catClass]"></span>
                                                <div>
                                                    <p x-text="item.name" class="text-[11px] font-black uppercase"></p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="item.qty + ' ' + item.unit"></p>
                                                </div>
                                            </div>
                                            <button @click.stop="removeItem(item.id)"
                                                    class="w-6 h-6 border border-gray-200 text-gray-400 hover:bg-red-50 hover:border-red-300 hover:text-red-500 transition-colors text-xs font-black flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="pantryItems.length === 0 && dragId === null" class="py-8 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">VOORRAADKAST IS LEEG</p>
                                </div>

                                <div x-show="dragId !== null"
                                     class="mt-3 py-2.5 border-2 border-dashed text-center text-[9px] font-black uppercase tracking-widest select-none pointer-events-none transition-all duration-100"
                                     :class="dragOver === 'pantry' ? 'border-brand text-brand' : 'border-gray-200 text-gray-300'">
                                    HIER NEERZETTEN
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

                        <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">

                            <h2 class="text-xl font-black uppercase italic text-center mb-5">JOUW KEUKEN</h2>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">TOTAAL</span>
                                    <span class="text-[12px] font-black" x-text="items.length + ' ITEMS'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄ KOELKAST</span>
                                    <span class="text-[12px] font-black" x-text="fridgeItems.length"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">❄❄ VRIEZER</span>
                                    <span class="text-[12px] font-black" x-text="freezerItems.length"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">VOORRAADKAST</span>
                                    <span class="text-[12px] font-black" x-text="pantryItems.length"></span>
                                </div>
                            </div>

                            <button @click="showAddModal = true"
                                    class="w-full bg-brand text-white text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                + PRODUCT TOEVOEGEN
                            </button>

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
                           x-text="'JE HEBT ' + items.length + ' PRODUCTEN IN HUIS. WIJ BEDENKEN HET RECEPT.'"></p>
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

            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative bg-white border-2 border-black shadow-[8px_8px_0px_0px_var(--hot-pink)] w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <button @click="showAddModal = false"
                        class="absolute -top-4 -right-4 w-10 h-10 rounded-full bg-black text-white flex items-center justify-center text-xl font-black border-2 border-black hover:bg-gray-800 transition-colors z-20">
                    ✕
                </button>

                <div class="p-8">

                    <h2 class="text-3xl font-black uppercase italic mb-6">PRODUCT TOEVOEGEN</h2>

                    {{-- Ingredient search --}}
                    <div class="mb-4">
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">INGREDIËNT</label>

                        {{-- Selected chip --}}
                        <div x-show="newItem.ingredient_id"
                             class="flex items-center justify-between border-2 border-black px-4 py-3 bg-[var(--lime)]">
                            <div>
                                <p x-text="newItem.ingredient_name" class="text-[11px] font-black uppercase"></p>
                                <p x-text="newItem.ingredient_category" class="text-[9px] font-bold uppercase text-gray-600"></p>
                            </div>
                            <button type="button" @click="clearIngredient()" class="text-sm font-black hover:opacity-60 ml-4">✕</button>
                        </div>

                        {{-- Search input + dropdown --}}
                        <div x-show="!newItem.ingredient_id" class="relative">
                            <div class="flex items-center border-2 border-black px-4 py-3 bg-white">
                                <svg class="w-4 h-4 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                                </svg>
                                <input type="text"
                                       x-model="newItem.search"
                                       @input.debounce.250ms="searchIngredients()"
                                       @keydown.escape="newItem.searchResults = []"
                                       placeholder="ZOEK EEN INGREDIËNT..."
                                       class="w-full text-[11px] font-black uppercase tracking-widest placeholder-gray-400 outline-none bg-transparent">
                            </div>
                            <div x-show="newItem.searchResults.length > 0"
                                 class="absolute top-full left-0 right-0 bg-white border-2 border-t-0 border-black shadow-[4px_4px_0px_0px_#000] z-30 max-h-48 overflow-y-auto">
                                <template x-for="result in newItem.searchResults" :key="result.id">
                                    <button type="button"
                                            @click="selectIngredient(result)"
                                            class="w-full flex items-center justify-between px-4 py-2.5 text-left hover:bg-[var(--pink-soft)] border-b border-gray-100 last:border-0 transition-colors">
                                        <span x-text="result.name" class="text-[11px] font-black uppercase"></span>
                                        <span x-text="result.category" class="text-[9px] font-bold uppercase text-gray-400"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

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

                    <div class="mb-8">
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2 text-gray-500">BEWAARPLAATS</label>
                        <div class="flex gap-2">
                            <button type="button"
                                    @click="newItem.location = 'fridge'"
                                    :class="newItem.location === 'fridge'
                                        ? 'bg-[var(--lime)] border-black text-black shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                ❄ KOELKAST
                            </button>
                            <button type="button"
                                    @click="newItem.location = 'freezer'"
                                    :class="newItem.location === 'freezer'
                                        ? 'bg-gray-800 border-gray-800 text-white shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                ❄❄ VRIEZER
                            </button>
                            <button type="button"
                                    @click="newItem.location = 'pantry'"
                                    :class="newItem.location === 'pantry'
                                        ? 'bg-[var(--yellow)] border-black text-black shadow-[2px_2px_0px_0px_#000]'
                                        : 'bg-white border-gray-300 text-gray-500'"
                                    class="flex-1 text-[9px] font-black uppercase tracking-widest py-2.5 border-2 transition-all duration-75">
                                KAST
                            </button>
                        </div>
                    </div>

                    <button @click="addItem()"
                            class="w-full bg-brand text-white text-base font-black uppercase italic tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                        TOEVOEGEN
                    </button>

                </div>
            </div>
        </div>

        <x-footer />

        @livewireScripts

        <script>
        function inventaris() {
            return {
                activeFilter: 'alles',
                showAddModal: false,
                newItem: {
                    ingredient_id: null,
                    ingredient_name: '',
                    ingredient_category: '',
                    search: '',
                    searchResults: [],
                    qty: '1',
                    unit: 'stuks',
                    location: 'fridge',
                },
                items: window.__inventoryItems || [],

                get fridgeItems()   { return this.items.filter(i => i.location === 'fridge') },
                get freezerItems()  { return this.items.filter(i => i.location === 'freezer') },
                get pantryItems()   { return this.items.filter(i => i.location === 'pantry') },

                dragId: null,
                dragOver: null,
                dragCounts: { fridge: 0, freezer: 0, pantry: 0 },

                csrf() {
                    return document.querySelector('meta[name="csrf-token"]').content;
                },

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
                        const item = this.items.find(i => i.id === this.dragId);
                        if (item && item.location !== loc) {
                            item.location = loc;
                            fetch(`/mijn-voorraad/items/${item.id}`, {
                                method: 'PATCH',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf() },
                                body: JSON.stringify({ location: loc }),
                            });
                        }
                    }
                    this.dragId = null;
                    this.dragOver = null;
                    this.dragCounts = { fridge: 0, freezer: 0, pantry: 0 };
                },

                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    fetch(`/mijn-voorraad/items/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': this.csrf() },
                    });
                },

                async searchIngredients() {
                    const q = this.newItem.search.trim();
                    if (!q) { this.newItem.searchResults = []; return; }
                    const res = await fetch(`/mijn-voorraad/ingredients?q=${encodeURIComponent(q)}`);
                    this.newItem.searchResults = await res.json();
                },

                selectIngredient(ingredient) {
                    this.newItem.ingredient_id       = ingredient.id;
                    this.newItem.ingredient_name     = ingredient.name;
                    this.newItem.ingredient_category = ingredient.category;
                    this.newItem.search              = '';
                    this.newItem.searchResults       = [];
                },

                clearIngredient() {
                    this.newItem.ingredient_id       = null;
                    this.newItem.ingredient_name     = '';
                    this.newItem.ingredient_category = '';
                },

                async addItem() {
                    if (!this.newItem.ingredient_id) return;
                    const res = await fetch('/mijn-voorraad/items', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf() },
                        body: JSON.stringify({
                            ingredient_id: this.newItem.ingredient_id,
                            quantity:      this.newItem.qty,
                            unit:          this.newItem.unit,
                            location:      this.newItem.location,
                        }),
                    });
                    if (res.ok) {
                        const item = await res.json();
                        this.items.push(item);
                    }
                    this.newItem = {
                        ingredient_id: null, ingredient_name: '', ingredient_category: '',
                        search: '', searchResults: [], qty: '1', unit: 'stuks', location: 'fridge',
                    };
                    this.showAddModal = false;
                },
            }
        }
        </script>
    </body>
</html>
