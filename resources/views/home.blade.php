<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hapklaar</title>
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-white">

        <x-navbar />

        {{-- ============================================================
             HERO
        ============================================================ --}}
        <section class="bg-[var(--pink-soft)] border-b-2 border-black">
            <div class="max-w-6xl mx-auto px-6 py-10 md:py-16 flex flex-col md:flex-row items-center gap-12 md:gap-16">

                {{-- Left --}}
                <div class="flex-1 min-w-0 w-full">
                    <h1 class="text-5xl sm:text-6xl md:text-[5.25rem] font-black uppercase leading-[0.85] mb-6 tracking-tight italic">
                        LEKKER VRETEN<br>
                        <span class="bg-[var(--lime)] px-3 py-1 inline-block ml-8 sm:ml-0" style="transform: skewX(-8deg); box-shadow: 5px 5px 0px 0px #000;">
                            <span style="display: inline-block; transform: skewX(8deg);">ZONDER STRESS.</span>
                        </span>
                    </h1>
                    <p class="text-gray-700 mb-9 leading-relaxed text-base max-w-sm">
                        Geen keuzestress. Geen ingewikkelde shit.<br>
                        Gewoon vreten wat de pot schaft (of wat er nog in je koelkast ligt).
                    </p>
                    <div class="flex gap-4 flex-col sm:flex-row sm:flex-wrap">
                        <a href="{{ route('ontdekken') }}"
                           class="text-center text-[12px] font-black uppercase tracking-widest bg-brand text-white no-underline px-7 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_var(--lime)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_var(--lime)] transition-all duration-75">
                            ONTDEK RECEPTEN
                        </a>
                        <a href="{{ route('mijn-keuken') }}"
                           class="text-center text-[12px] font-black uppercase tracking-widest bg-white text-black no-underline px-7 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_var(--pink)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_var(--pink)] transition-all duration-75">
                            SCAN JE KOELKAST
                        </a>
                    </div>
                </div>

                {{-- Right: Image card --}}
                <div class="flex-shrink-0 relative">
                    <div class="relative w-60 md:w-72" style="transform: rotate(3deg);">
                        {{-- Yellow image box --}}
                        <div class="border-4 border-black bg-[var(--yellow)]" style="box-shadow: 6px 6px 0px 0px var(--hot-pink);">
                            <div class="aspect-square bg-[var(--yellow)] flex items-center justify-center text-[var(--yellow)] overflow-hidden">
                                <img src="{{ asset('images/kater_bowl.png') }}" alt="Ultieme Katerbowl" class="w-full h-full object-cover">
                            </div>
                            <div class="bg-white px-4 py-3 border-t-4 border-black">
                                <span class="text-black font-black uppercase tracking-widest text-sm italic">ULTIEME KATER BOWL</span>
                            </div>
                        </div>
                        {{-- HOT! badge --}}
                        <div class="absolute -top-3 -right-4 bg-[var(--lime)] border-2 border-black px-3 py-1 font-black text-sm uppercase shadow-[5px_5px_0px_0px_#000]">
                            HOT!
                        </div>
                        {{-- Star circle --}}
                        <div class="absolute -bottom-6 -left-10 w-14 h-14 rounded-full bg-purple-500 border-2 border-black flex items-center justify-center z-10">
                            <span class="text-black text-xl font-black">★</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ============================================================
             TRENDING RECEPTEN (Livewire)
        ============================================================ --}}
        <section class="py-10 md:py-16 border-b-2 border-black bg-white">
            <div class="max-w-6xl mx-auto px-6">

                <div class="flex items-center gap-4 mb-8 md:mb-10 flex-wrap">
                    <h2 class="text-2xl md:text-4xl font-black uppercase">TRENDING RECEPTEN</h2>
                    <span class="bg-[var(--lime)] text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 border-2 border-black">HOT &amp; FRESH</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($recepten as $recept)
                    <div class="border-2 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col bg-white">
                        <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                            @if($recept->image_path)
                                <img src="{{ asset('storage/' . $recept->image_path) }}" alt="{{ $recept->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200"></div>
                            @endif
                            @if($recept->badge)
                                <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-brand text-white">{{ $recept->badge }}</span>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-lg leading-tight mb-3">{{ $recept->title }}</h3>

                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase text-gray-500 tracking-wide flex-wrap">
                                @if($recept->prep_time_minutes)
                                    <span>⏱ {{ $recept->prep_time_minutes }} MIN</span>
                                @endif
                                @if($recept->calories_per_portion)
                                    <span>⚡ {{ (int)$recept->calories_per_portion }} KCAL</span>
                                @endif
                                @if($recept->afwas_score)
                                    <span>🍽 {{ str_repeat('|', (int)$recept->afwas_score) }}</span>
                                @endif
                            </div>

                            <a href="{{ route('recept', $recept->id) }}"
                               class="mt-auto text-center text-[11px] font-black uppercase tracking-widest bg-brand text-white no-underline px-4 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                LAAT ME ZIEN!
                            </a>
                        </div>
                    </div>
                    @endforeach

                </div>

            </div>
        </section>

        {{-- ============================================================
             HOE WERKT HET?
        ============================================================ --}}
        <section class="py-10 md:py-16 bg-[var(--pink-soft)]">
            <div class="max-w-6xl mx-auto px-6">
                <h2 class="text-3xl font-black uppercase italic text-center mb-12 md:mb-16 text-[#2B1B1B]">HOE WERKT HET?</h2>

                <div class="relative flex flex-col md:flex-row items-center md:items-start md:justify-between gap-10 md:gap-16">
                    {{-- Dashed connector line: horizontal on desktop, vertical on mobile --}}
                    <div class="hidden md:block absolute top-8 left-[12%] right-[12%] border-t-2 border-dashed border-black z-0 pointer-events-none"></div>
                    <div class="md:hidden absolute top-8 bottom-8 left-1/2 -translate-x-1/2 border-l-2 border-dashed border-black z-0 pointer-events-none"></div>

                    @foreach([
                        ['n' => 1, 'bg' => 'var(--lime)',   'num' => 'text-black',          'title' => 'VIND', 'title_color' => 'text-[var(--pink)]', 'desc' => 'Kies een recept op basis van je budget of koelkast inhoud.'],
                        ['n' => 2, 'bg' => 'var(--pink)',   'num' => 'text-white',          'title' => 'KOOK', 'title_color' => 'text-[var(--pink)]', 'desc' => 'Volg de stappen zonder dat je brein explodeert.'],
                        ['n' => 3, 'bg' => '#a855f7',       'num' => 'text-white',          'title' => 'EET',  'title_color' => 'text-purple-600',     'desc' => 'Vreten maar. De afwas is een probleem voor morgen.'],
                    ] as $step)
                        <div class="flex-1 flex flex-col items-center text-center relative z-10">
                            {{-- Numbered circle --}}
                            <div class="w-16 h-16 rounded-full border-2 border-black flex items-center justify-center shadow-[4px_4px_0px_0px_#000]"
                                 style="background-color: {{ $step['bg'] }};">
                                <span class="{{ $step['num'] }} text-2xl font-black">{{ $step['n'] }}</span>
                            </div>
                            {{-- Card --}}
                            <div class="mt-6 bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000] px-5 py-5 w-full max-w-[220px]">
                                <h3 class="text-xl font-black uppercase italic mb-2 {{ $step['title_color'] }}">{{ $step['title'] }}</h3>
                                <p class="text-sm text-gray-700 leading-snug">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============================================================
             CTA: NOOIT MEER KEUZESTRESS (Alpine.js)
        ============================================================ --}}
        <section class="bg-white py-10">
            <div class="max-w-7xl mx-auto px-4 md:px-1">
                <div class="grid grid-cols-1 md:grid-cols-2 md:min-h-[420px] border-[3px] border-black" style="box-shadow: 6px 0 0 0 var(--lime), 0 6px 0 0 var(--lime);">

                {{-- Left: Brand pink --}}
                <div class="bg-[var(--pink)] border-b-2 md:border-b-0 md:border-r-2 border-black p-8 md:p-16 flex flex-col justify-center">
                    <h2 class="text-4xl md:text-5xl font-black uppercase italic text-white leading-none mb-5">
                        NOOIT MEER<br>KEUZESTRESS.
                    </h2>
                    <p class="text-white/75 mb-8 text-sm leading-relaxed max-w-sm">
                        Ontvang wekelijks een curated lijst met recepten die wél te doen zijn op een dinsdagavond.
                    </p>
                    <div>
                        <button
                            type="button"
                            class="text-[11px] font-black uppercase tracking-widest bg-black text-white px-6 py-3 border-2 border-black shadow-[5px_5px_0px_0px_var(--lime)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[3px_3px_0px_0px_var(--lime)] transition-all duration-75 cursor-pointer">
                            IK WIL DIT!
                        </button>
                    </div>
                </div>

                {{-- Right: App mockup --}}
                <div class="bg-[var(--pink)] md:bg-white p-8 md:p-16 flex items-center justify-center">
                    <div class="w-64 bg-white border-2 border-black overflow-hidden" style="box-shadow: 8px 8px 0px 0px var(--hot-pink);">
                        {{-- Mockup header --}}
                        <div class="bg-[var(--lime)] border-b-2 border-black px-3 py-2">
                            <span class="text-[10px] font-black uppercase tracking-wider">HAPKLAAR APP</span>
                        </div>
                        {{-- Mockup content --}}
                        <div class="p-3 space-y-2">
                            <div class="h-24 bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-200 text-3xl">🥑</div>
                            <div class="border-2 border-brand p-2">
                                <div class="text-[8px] font-black uppercase bg-brand text-white inline-block px-1 mb-1">NIEUW!</div>
                                <div class="text-[10px] font-black uppercase leading-tight">SPICY AVOCADO TOAST MET EEN KRING-EYE</div>
                                <div class="text-[8px] text-gray-400 mt-2 uppercase font-bold">AFGEROND: 65%</div>
                                <div class="h-1.5 bg-gray-100 mt-1 border border-gray-200">
                                    <div class="h-full bg-brand" style="width:65%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </section>

        <x-footer />

        @livewireScripts
    </body>
</html>
