<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ultieme Kater Ramen - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-8" x-data="{ tab: 'stappen', portions: 2 }">
            <div class="max-w-6xl mx-auto px-6">

                {{-- ============================================================
                     RECIPE HEADER
                ============================================================ --}}
                <div class="mb-5">
                    <h1 class="text-4xl font-black uppercase italic leading-tight mb-1">ULTIEME KATER RAMEN</h1>
                    <p class="text-brand font-bold italic text-sm mb-3">De fix die je brein terug aanzet.</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000]">15 MIN</span>
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000]">650 KCAL</span>
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-violet-200 shadow-[2px_2px_0px_0px_#000]">AFWAS-SCORE 1</span>
                    </div>
                </div>

                {{-- ============================================================
                     TWO-COLUMN LAYOUT
                ============================================================ --}}
                <div class="flex gap-7 items-start">

                    {{-- LEFT: image + controls + steps --}}
                    <div class="flex-1 min-w-0">

                        {{-- Recipe image --}}
                        <div class="relative border-2 border-black overflow-hidden mb-4" style="aspect-ratio: 16/10;">
                            <img src="{{ asset('images/kater_pasta.png') }}"
                                 alt="Ultieme Kater Ramen"
                                 class="w-full h-full object-cover">
                            <button class="absolute inset-0 flex items-center justify-center group">
                                <span class="w-16 h-16 rounded-full bg-brand flex items-center justify-center shadow-[3px_3px_0px_0px_#000] group-hover:scale-105 transition-transform duration-100">
                                    <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </span>
                            </button>
                            <span class="absolute bottom-3 left-3 text-[8px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-2 py-1">RECEPT VIDEO</span>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-3 mb-5">
                            <button class="flex items-center gap-2 bg-brand text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 016 0v6a3 3 0 01-3 3z"/>
                                </svg>
                                VOICE CONTROL AAN
                            </button>
                            <button class="flex items-center gap-2 bg-white text-black text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                SHARE
                            </button>
                        </div>

                        {{-- Tabs --}}
                        <div class="flex gap-6 border-b-2 border-black mb-6 items-end">
                            <button @click="tab = 'stappen'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'stappen'" class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">STAPPEN</span>
                                <span x-show="tab !== 'stappen'" x-cloak class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">STAPPEN</span>
                            </button>
                            <button @click="tab = 'voedingswaarden'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'voedingswaarden'" x-cloak class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">VOEDINGSWAARDEN</span>
                                <span x-show="tab !== 'voedingswaarden'" class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">VOEDINGSWAARDEN</span>
                            </button>
                            <button @click="tab = 'reviews'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'reviews'" x-cloak class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">REVIEWS</span>
                                <span x-show="tab !== 'reviews'" class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">REVIEWS</span>
                            </button>
                        </div>

                        {{-- STAPPEN --}}
                        <div x-show="tab === 'stappen'">
                            @php
                                $steps = [
                                    ['num' => 1, 'text' => 'Kook de eieren precies 6 minuten in kokend water. Schrik ze daarna direct af onder de koude kraan.'],
                                    ['num' => 2, 'text' => 'Kook 500ml water met de kruidenmixjes. Voeg de noedels toe en kook voor 2 minuten.'],
                                    ['num' => 3, 'text' => 'Roer de sambal en sesamolie erdoorheen. Gooi de spinazie erbij op het allerlaatste moment.'],
                                    ['num' => 4, 'text' => 'Giet in een grote kom, leg het ei erop (doormidden gesneden!) en strooi de lente-ui erover.'],
                                ];
                            @endphp
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($steps as $step)
                                    <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] flex overflow-hidden">
                                        {{-- Step thumbnail --}}
                                        <div class="relative flex-shrink-0 w-28 border-r-2 border-black">
                                            <img src="{{ asset('images/kater_pasta.png') }}"
                                                 alt="Stap {{ $step['num'] }}"
                                                 class="w-full h-full object-cover">
                                            <span class="absolute top-1 left-1 text-[6px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1 py-0.5 leading-tight">STAP {{ $step['num'] }} VIDEO</span>
                                            <button class="absolute inset-0 flex items-center justify-center">
                                                <span class="w-8 h-8 rounded-full bg-brand flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                                                    <svg class="w-3 h-3 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </span>
                                            </button>
                                            <span class="absolute bottom-1 right-1 text-[7px] font-black bg-black text-white px-1 py-0.5">0:18</span>
                                        </div>
                                        {{-- Step content --}}
                                        <div class="flex-1 p-3">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="w-7 h-7 rounded-full border-2 border-black flex items-center justify-center text-xs font-black flex-shrink-0">{{ $step['num'] }}</span>
                                                <span class="text-[8px] font-black uppercase tracking-widest text-brand cursor-pointer hover:underline">JUMP NAAR</span>
                                            </div>
                                            <p class="text-[11px] font-medium leading-snug text-gray-800">{{ $step['text'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- VOEDINGSWAARDEN --}}
                        <div x-show="tab === 'voedingswaarden'" x-cloak>
                            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-8 text-center">
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Voedingswaarden komen hier</p>
                            </div>
                        </div>

                        {{-- REVIEWS --}}
                        <div x-show="tab === 'reviews'" x-cloak>
                            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-8 text-center">
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Reviews komen hier</p>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT: Ingredients card --}}
                    <div class="w-72 flex-shrink-0 sticky top-6">
                        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                            {{-- Header + portions counter --}}
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base font-black uppercase">INGREDIËNTEN</h2>
                                <div class="flex items-center border-2 border-black">
                                    <button @click="portions = Math.max(1, portions - 1)"
                                            class="w-8 h-8 flex items-center justify-center font-black text-base border-r-2 border-black hover:bg-[var(--pink-soft)] transition-colors">−</button>
                                    <span class="w-8 h-8 flex items-center justify-center font-black text-sm bg-[var(--lime)]" x-text="portions">2</span>
                                    <button @click="portions++"
                                            class="w-8 h-8 flex items-center justify-center font-black text-base border-l-2 border-black hover:bg-[var(--pink-soft)] transition-colors">+</button>
                                </div>
                            </div>

                            {{-- Ingredients list --}}
                            @php
                                $ingredients = [
                                    '2 Pakjes Instant Ramen (Kip smaak)',
                                    '1/2 EL Sambal Oelek (voor die kick)',
                                    '2 Eieren (6 minuutjes koken)',
                                    'Handje Verse Spinazie',
                                    '1 TL Sesamolie',
                                    'Lente-ui (voor de vitaminen)',
                                ];
                            @endphp
                            <ul class="divide-y divide-gray-100">
                                @foreach($ingredients as $ingredient)
                                    <li class="flex items-center gap-3 py-2.5">
                                        <svg class="w-3 h-3 flex-shrink-0 text-brand" fill="currentColor" viewBox="0 0 12 12">
                                            <path d="M6 0L7.5 4.5L12 6L7.5 7.5L6 12L4.5 7.5L0 6L4.5 4.5Z"/>
                                        </svg>
                                        <span class="text-[12px] font-medium leading-snug">{{ $ingredient }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Nutrition bars --}}
                            <div class="border-t-2 border-black mt-4 pt-4 space-y-3">
                                @php
                                    $nutrients = [
                                        ['label' => 'EIWITTEN',      'value' => '28G', 'pct' => 34, 'color' => 'bg-brand'],
                                        ['label' => 'KOOLHYDRATEN',  'value' => '84G', 'pct' => 65, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'VETTEN',        'value' => '22G', 'pct' => 27, 'color' => 'bg-violet-400'],
                                    ];
                                @endphp
                                @foreach($nutrients as $n)
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ $n['label'] }}</span>
                                            <span class="text-[9px] font-black uppercase">{{ $n['value'] }}</span>
                                        </div>
                                        <div class="h-3 bg-gray-100 border border-black overflow-hidden">
                                            <div class="h-full {{ $n['color'] }}" style="width: {{ $n['pct'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ============================================================
                     ADD TO SHOPPING LIST
                ============================================================ --}}
                <div class="flex justify-center mt-10 mb-4">
                    <button class="text-[11px] font-black uppercase tracking-widest px-16 py-5 border-2 border-black bg-brand text-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                        VOEG TOE AAN BOODSCHAPPENLIJST
                    </button>
                </div>

            </div>
        </main>

        <x-footer />

    </body>
</html>
