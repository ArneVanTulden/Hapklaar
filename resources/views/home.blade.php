<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hapklaar</title>

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
        <section class="bg-[#FFF0F5] border-b-2 border-black">
            <div class="max-w-6xl mx-auto px-6 py-16 flex items-center gap-16">

                {{-- Left --}}
                <div class="flex-1 min-w-0">
                    <h1 class="text-[5.25rem] font-black uppercase leading-[0.85] mb-6 tracking-tight italic">
                        LEKKER VRETEN<br>
                        <span class="bg-[#C8FF00] px-3 py-1 inline-block" style="transform: skewX(-8deg); box-shadow: 5px 5px 0px 0px #000;">
                            <span style="display: inline-block; transform: skewX(8deg);">ZONDER STRESS.</span>
                        </span>
                    </h1>
                    <p class="text-gray-700 mb-9 leading-relaxed text-base max-w-sm">
                        Geen keuzestress. Geen ingewikkelde shit.<br>
                        Gewoon vreten wat de pot schaft (of wat er nog in je koelkast ligt).
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        <a href="#"
                           class="text-[12px] font-black uppercase tracking-widest bg-brand text-white no-underline px-7 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_#C8FF00] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#C8FF00] transition-all duration-75">
                            ONTDEK RECEPTEN
                        </a>
                        <a href="#"
                           class="text-[12px] font-black uppercase tracking-widest bg-white text-black no-underline px-7 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_#E5006E] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#E5006E] transition-all duration-75">
                            SCAN JE KOELKAST
                        </a>
                    </div>
                </div>

                {{-- Right: Image card --}}
                <div class="flex-shrink-0 relative">
                    <div class="relative w-72" style="transform: rotate(3deg);">
                        {{-- Yellow image box --}}
                        <div class="border-4 border-black bg-[#FFD600]" style="box-shadow: 6px 6px 0px 0px #FF1493;">
                            <div class="aspect-square bg-[#FFD600] flex items-center justify-center text-[#FFD600] overflow-hidden">
                                <img src="{{ asset('images/kater_bowl.png') }}" alt="Ultieme Katerbowl" class="w-full h-full object-cover">
                            </div>
                            <div class="bg-white px-4 py-3 border-t-4 border-black">
                                <span class="text-black font-black uppercase tracking-widest text-sm italic">ULTIEME KATER BOWL</span>
                            </div>
                        </div>
                        {{-- HOT! badge --}}
                        <div class="absolute -top-3 -right-4 bg-[#C8FF00] border-2 border-black px-3 py-1 font-black text-sm uppercase shadow-[5px_5px_0px_0px_#000]">
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
        <section class="py-16 border-b-2 border-black bg-white">
            <div class="max-w-6xl mx-auto px-6">

                <div class="flex items-center gap-4 mb-10">
                    <h2 class="text-4xl font-black uppercase">TRENDING RECEPTEN</h2>
                    <span class="bg-[#C8FF00] text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 border-2 border-black">HOT &amp; FRESH</span>
                </div>

                <div class="grid grid-cols-3 gap-6">

                    <!-- Card 1 -->
                    <div class="border-2 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col bg-white">
                        <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                            <img src="{{ asset('images/kater_pasta.png') }}" alt="Snelle Pasta met Tomaat" class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-brand text-white">STUDENT FAVORIET</span>
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-lg leading-tight mb-3">Snelle Pasta met Tomaat</h3>

                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase text-gray-500 tracking-wide flex-wrap">
                                <span>⏱ 20 MIN</span>
                                <span>⚡ 450 KCAL</span>
                                <span>🍽 |||</span>
                            </div>

                            <a href="#"
                               class="mt-auto text-center text-[11px] font-black uppercase tracking-widest bg-brand text-white no-underline px-4 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                LAAT ME ZIEN!
                            </a>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="border-2 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col bg-white">
                        <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                            <img src="{{ asset('images/budget_nachos.png') }}" alt="Ovengebakken Zoete Aardappel" class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-[#FF6B00] text-white">HOT DEAL</span>
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-lg leading-tight mb-3">Ovengebakken Zoete Aardappel</h3>

                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase text-gray-500 tracking-wide flex-wrap">
                                <span>⏱ 35 MIN</span>
                                <span>⚡ 320 KCAL</span>
                                <span>🍽 ||||</span>
                            </div>

                            <a href="#"
                               class="mt-auto text-center text-[11px] font-black uppercase tracking-widest bg-brand text-white no-underline px-4 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                LAAT ME ZIEN!
                            </a>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="border-2 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col bg-white">
                        <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                            <img src="{{ asset('images/brakke_bowl.png') }}" alt="Rijstsalade met Groenten" class="w-full h-full object-cover">
                            <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-[#C8FF00] text-black">LAAGSTE AFWAS</span>
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-lg leading-tight mb-3">Rijstsalade met Groenten</h3>

                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase text-gray-500 tracking-wide flex-wrap">
                                <span>⏱ 15 MIN</span>
                                <span>⚡ 280 KCAL</span>
                                <span>🍽 ||</span>
                            </div>

                            <a href="#"
                               class="mt-auto text-center text-[11px] font-black uppercase tracking-widest bg-brand text-white no-underline px-4 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                LAAT ME ZIEN!
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        {{-- ============================================================
             HOE WERKT HET?
        ============================================================ --}}
        <section class="py-20 border-b-2 border-black bg-white">
            <div class="max-w-4xl mx-auto px-6">
                <h2 class="text-4xl font-black uppercase text-center mb-16">HOE WERKT HET?</h2>

                <div class="relative flex items-start justify-between gap-4">
                    {{-- Dashed connector line --}}
                    <div class="absolute top-10 left-[calc(16.67%+10px)] right-[calc(16.67%+10px)] border-t-2 border-dashed border-black z-0 pointer-events-none"></div>

                    {{-- VIND --}}
                    <div class="flex-1 flex flex-col items-center text-center relative z-10">
                        <div class="w-20 h-20 rounded-full bg-[#C8FF00] border-2 border-black flex items-center justify-center mb-5 shadow-[4px_4px_0px_0px_#000]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-2">VIND</h3>
                        <p class="text-sm text-gray-500 max-w-[160px] leading-snug">Kies een recept op basis van je budget of koelkast inhoud.</p>
                    </div>

                    {{-- KOOK --}}
                    <div class="flex-1 flex flex-col items-center text-center relative z-10">
                        <div class="w-20 h-20 rounded-full bg-brand border-2 border-black flex items-center justify-center mb-5 shadow-[4px_4px_0px_0px_#000]">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-2 text-brand">KOOK</h3>
                        <p class="text-sm text-gray-500 max-w-[160px] leading-snug">Volg de stappen zonder dat je brein explodeert.</p>
                    </div>

                    {{-- EET --}}
                    <div class="flex-1 flex flex-col items-center text-center relative z-10">
                        <div class="w-20 h-20 rounded-full bg-purple-600 border-2 border-black flex items-center justify-center mb-5 shadow-[4px_4px_0px_0px_#000]">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black uppercase mb-2 text-purple-600">EET</h3>
                        <p class="text-sm text-gray-500 max-w-[160px] leading-snug">Vreten maar. De afwas is een probleem voor morgen.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================
             CTA: NOOIT MEER KEUZESTRESS (Alpine.js)
        ============================================================ --}}
        <section>
            <div class="grid grid-cols-2 min-h-96">

                {{-- Left: Brand pink --}}
                <div class="bg-brand border-r-2 border-black p-14 flex flex-col justify-center">
                    <h2 class="text-5xl font-black uppercase text-white leading-none mb-5">
                        NOOIT MEER<br>KEUZESTRESS.
                    </h2>
                    <p class="text-white/75 mb-8 text-sm leading-relaxed max-w-xs">
                        Ontvang wekelijks een curated lijst met recepten die wél te doen zijn op een dinsdagavond.
                    </p>
                    <div>
                        <button
                            type="button"
                            class="text-[11px] font-black uppercase tracking-widest bg-black text-white px-6 py-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,0.4)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,0.4)] transition-all duration-75 cursor-pointer">
                            IK WIL DIT!
                        </button>
                    </div>
                </div>

                {{-- Right: App mockup --}}
                <div class="bg-[#F0F0F0] p-14 flex items-center justify-center border-t-2 border-black">
                    <div class="w-52 bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000] overflow-hidden">
                        {{-- Mockup header --}}
                        <div class="bg-[#C8FF00] border-b-2 border-black px-3 py-2">
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
        </section>

        <x-footer />

    </body>
</html>
