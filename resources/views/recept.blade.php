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
                        <div x-show="tab === 'voedingswaarden'" x-cloak class="space-y-6">

                            {{-- Calorieën --}}
                            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-6">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">CALORIEËN PER PORTIE</p>
                                <p class="text-5xl font-black italic text-brand mb-3">650 KCAL</p>
                                <div class="h-3 bg-gray-100 border border-black overflow-hidden mb-1">
                                    <div class="h-full bg-[var(--lime)]" style="width: 32%"></div>
                                </div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">32% VAN JE DAGELIJKSE INNAME</p>
                            </div>

                            {{-- Macronutriënten --}}
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest mb-3">MACRONUTRIËNTEN</p>
                                <div class="grid grid-cols-3 gap-3">
                                    @php
                                        $macros = [
                                            ['label' => 'EIWITTEN',     'value' => '28G', 'sub' => null,              'pct' => 34, 'color' => 'bg-brand'],
                                            ['label' => 'KOOLHYDRATEN', 'value' => '84G', 'sub' => 'WAARVAN SUIKERS: 12G', 'pct' => 65, 'color' => 'bg-[var(--lime)]'],
                                            ['label' => 'VETTEN',       'value' => '22G', 'sub' => 'WAARVAN VERZADIGD: 7G', 'pct' => 27, 'color' => 'bg-violet-400'],
                                        ];
                                    @endphp
                                    @foreach($macros as $m)
                                        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-4">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $m['label'] }}</p>
                                            <p class="text-2xl font-black mb-2">{{ $m['value'] }}</p>
                                            <div class="h-2 bg-gray-100 border border-black overflow-hidden mb-2">
                                                <div class="h-full {{ $m['color'] }}" style="width: {{ $m['pct'] }}%"></div>
                                            </div>
                                            @if($m['sub'])
                                                <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">{{ $m['sub'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Vitaminen & Mineralen --}}
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest mb-3">VITAMINEN & MINERALEN</p>
                                @php
                                    $vitamins = [
                                        ['label' => 'VITAMINE A',  'value' => '450μg', 'pct' => 56, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'VITAMINE C',  'value' => '12mg',  'pct' => 13, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'VITAMINE D',  'value' => '2.5μg', 'pct' => 25, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'IJZER',       'value' => '4.2mg', 'pct' => 30, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'CALCIUM',     'value' => '180mg', 'pct' => 18, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'KALIUM',      'value' => '850mg', 'pct' => 43, 'color' => 'bg-[var(--lime)]'],
                                        ['label' => 'NATRIUM',     'value' => '1.8g',  'pct' => 78, 'color' => 'bg-brand'],
                                        ['label' => 'VEZELS',      'value' => '5g',    'pct' => 20, 'color' => 'bg-[var(--lime)]'],
                                    ];
                                @endphp
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($vitamins as $v)
                                        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-3">
                                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $v['label'] }}</p>
                                            <p class="text-lg font-black mb-2">{{ $v['value'] }}</p>
                                            <div class="h-1.5 bg-gray-100 border border-black overflow-hidden">
                                                <div class="h-full {{ $v['color'] }}" style="width: {{ $v['pct'] }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Labels --}}
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest mb-3">LABELS</p>
                                @php
                                    $labels = [
                                        ['text' => 'HIGH-PROTEIN', 'class' => 'bg-brand text-white'],
                                        ['text' => 'LOW-SUGAR',    'class' => 'bg-brand text-white'],
                                        ['text' => 'BALANCED',     'class' => 'bg-[var(--lime)] text-black'],
                                        ['text' => 'MEDITERRANEAN','class' => 'bg-[var(--lime)] text-black'],
                                        ['text' => 'DAIRY-FREE',   'class' => 'bg-violet-200 text-black'],
                                        ['text' => 'PEANUT-FREE',  'class' => 'bg-white text-black'],
                                    ];
                                @endphp
                                <div class="flex flex-wrap gap-2">
                                    @foreach($labels as $l)
                                        <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $l['class'] }}">{{ $l['text'] }}</span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Disclaimer --}}
                            <div class="border-l-4 border-brand pl-4">
                                <p class="text-xs italic text-gray-500">Voedingswaarden zijn een schatting per portie. Berekend door Edamam op basis van de ingrediënten.</p>
                            </div>

                        </div>

                        {{-- REVIEWS --}}
                        <div x-show="tab === 'reviews'" x-cloak class="space-y-5">

                            {{-- Rating summary --}}
                            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5 flex gap-8 items-center">
                                <div class="flex-shrink-0 text-center">
                                    <p class="text-6xl font-black leading-none">4.8</p>
                                    <div class="flex gap-0.5 justify-center my-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 text-brand" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">GEBASEERD OP 247 REVIEWS</p>
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    @php
                                        $bars = [
                                            ['label' => '5 STER', 'count' => 198, 'pct' => 80],
                                            ['label' => '4 STER', 'count' => 34,  'pct' => 14],
                                            ['label' => '3 STER', 'count' => 11,  'pct' => 4],
                                            ['label' => '2 STER', 'count' => 4,   'pct' => 2],
                                            ['label' => '1 STER', 'count' => 0,   'pct' => 0],
                                        ];
                                    @endphp
                                    @foreach($bars as $b)
                                        <div class="flex items-center gap-2">
                                            <span class="text-[8px] font-black uppercase tracking-widest w-10 flex-shrink-0">{{ $b['label'] }}</span>
                                            <div class="flex-1 h-2.5 bg-gray-100 border border-black overflow-hidden">
                                                <div class="h-full bg-[var(--lime)]" style="width: {{ $b['pct'] }}%"></div>
                                            </div>
                                            <span class="text-[8px] font-black w-6 text-right">{{ $b['count'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Action bar --}}
                            <div class="flex items-center justify-between">
                                <button class="flex items-center gap-2 bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    SCHRIJF EEN REVIEW
                                </button>
                                <div class="flex gap-1">
                                    @foreach(['MEEST RECENT', 'HOOGSTE', 'LAAGSTE', 'MET FOTO'] as $sort)
                                        <button class="text-[8px] font-black uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $sort === 'MEEST RECENT' ? 'bg-[var(--lime)]' : 'bg-white hover:bg-[var(--pink-soft)]' }} transition-colors">{{ $sort }}</button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Reviews --}}
                            @php
                                $reviews = [
                                    [
                                        'initial' => 'T',
                                        'name'    => 'THOMAS V.D. BILT',
                                        'badge'   => 'GEVERIFIEERDE STUDENT',
                                        'when'    => '3 UUR GELEDEN',
                                        'stars'   => 5,
                                        'title'   => 'HEEFT LETTERLIJK MIJN LEVEN GERED',
                                        'body'    => 'Na het lustrumdfeest gisteravond was ik ervan overtuigd dat ik nooit meer zou kunnen lopen. Deze ramen met die extra pindakaas hack? Absolute game changer. Heb ook wat sriracha toegevoegd voor de extra kick. 10/10 zou het weer maken na elke escalatie.',
                                        'photos'  => true,
                                        'helpful' => 42,
                                        'color'   => 'bg-brand text-white',
                                    ],
                                    [
                                        'initial' => 'L',
                                        'name'    => 'LAURA STEVENS',
                                        'badge'   => 'GEVERIFIEERDE STUDENT',
                                        'when'    => 'GISTEREN',
                                        'stars'   => 4,
                                        'title'   => 'SNEL EN GOEDKOOP',
                                        'body'    => 'Perfect voor als je geen zin hebt om naar de Apple te lopen en alleen nog maar wat eieren en ramen in de kast hebt liggen. De pindakaas klinkt raar maar maakt de saus super creamy. Iets te zout als je het hele kruidenzakje gebruikt, dus gebruik de helft!',
                                        'photos'  => false,
                                        'helpful' => 18,
                                        'color'   => 'bg-[var(--lime)] text-black',
                                    ],
                                    [
                                        'initial' => 'M',
                                        'name'    => 'MAARTEN DE J.',
                                        'badge'   => 'GEVERIFIEERDE STUDENT',
                                        'when'    => '3 DAGEN GELEDEN',
                                        'stars'   => 5,
                                        'title'   => 'ELKE CENT WAARD',
                                        'body'    => 'Ik heb dit nu al 3 keer gegeten deze week. Voor minder dan 2 euro per portie kun je niet klagen. De tip voor de voice control is trouwens goud als je met vette ramen-vingers je scherm niet wilt aanraken.',
                                        'photos'  => false,
                                        'helpful' => 29,
                                        'color'   => 'bg-violet-200 text-black',
                                    ],
                                ];
                            @endphp

                            @foreach($reviews as $review)
                                <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5">
                                    {{-- Header --}}
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <span class="w-9 h-9 rounded-full {{ $review['color'] }} border-2 border-black flex items-center justify-center text-sm font-black flex-shrink-0">{{ $review['initial'] }}</span>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[11px] font-black uppercase">{{ $review['name'] }}</span>
                                                    <span class="text-[7px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1.5 py-0.5">{{ $review['badge'] }}</span>
                                                </div>
                                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $review['when'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i < $review['stars'] ? 'text-brand' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    {{-- Body --}}
                                    <p class="text-[11px] font-black uppercase mb-1">{{ $review['title'] }}</p>
                                    <p class="text-[11px] text-gray-700 leading-relaxed mb-3">{{ $review['body'] }}</p>
                                    {{-- Photos --}}
                                    @if($review['photos'])
                                        <div class="flex gap-2 mb-3">
                                            @for($p = 0; $p < 2; $p++)
                                                <div class="w-20 h-16 border-2 border-black overflow-hidden">
                                                    <img src="{{ asset('images/kater_pasta.png') }}" class="w-full h-full object-cover" alt="review foto">
                                                </div>
                                            @endfor
                                        </div>
                                    @endif
                                    {{-- Footer --}}
                                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">WAS DIT HELPFUL?</span>
                                            <button class="flex items-center gap-1 bg-[var(--lime)] border-2 border-black px-2 py-1 shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                                <span class="text-[9px] font-black">{{ $review['helpful'] }}</span>
                                            </button>
                                        </div>
                                        <button class="text-[9px] font-black uppercase tracking-widest text-brand hover:underline">ANTWOORD</button>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Load more --}}
                            <div class="flex justify-center pt-2">
                                <button class="text-[10px] font-black uppercase tracking-widest px-12 py-4 border-2 border-black bg-white shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    LAAD MEER REVIEWS
                                </button>
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

        @livewireScripts
    </body>
</html>
