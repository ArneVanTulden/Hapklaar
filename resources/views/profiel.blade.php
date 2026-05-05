<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profiel - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-10">
            <div class="max-w-5xl mx-auto px-6">

                {{-- ============================================================
                     PAGE HEADER
                ============================================================ --}}
                <div class="mb-6">
                    <h1 class="text-[3.5rem] font-black uppercase italic leading-none mb-1">MIJN ACCOUNT</h1>
                    <p class="text-brand font-black uppercase italic text-base">JOUW EIGEN VRETPLEK OP HET INTERNET.</p>
                </div>

                {{-- ============================================================
                     USERNAME BAR
                ============================================================ --}}
                <div class="flex items-center justify-between bg-white border-2 border-black shadow-[4px_4px_0px_0px_var(--hot-pink)] px-5 py-4 mb-5">
                    <div>
                        <p class="text-[8px] font-black uppercase tracking-widest text-gray-400 mb-0.5">GEBRUIKERSNAAM</p>
                        <p class="text-2xl font-black uppercase leading-none">ARNE</p>
                    </div>
                    <button class="flex items-center gap-1.5 bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        PROFIEL BEWERKEN
                    </button>
                </div>

                {{-- ============================================================
                     PROFILE GRID: avatar | bio + stats
                ============================================================ --}}
                <div class="grid mb-6" style="grid-template-columns: 2fr 3fr; gap: 0;">

                    {{-- Avatar --}}
                    <div class="border-2 border-black border-r-0" style="box-shadow: 5px 5px 0px 0px var(--hot-pink);">
                        <div class="bg-[#E8453C] flex items-end justify-center overflow-hidden" style="min-height: 320px;">
                            <div class="text-[10rem] leading-none select-none" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));">
                                👨‍🍳
                            </div>
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="flex flex-col border-2 border-black" style="box-shadow: 5px 5px 0px 0px var(--hot-pink);">

                        {{-- Bio --}}
                        <div class="flex-1 bg-[#E8FFB0] border-b-2 border-black flex items-center justify-center p-8">
                            <p class="text-[11px] font-black uppercase tracking-widest text-gray-500 text-center">HIER KOMT DE BIO VAN DE GEBRUIKER</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2" style="min-height: 140px;">
                            <div class="flex flex-col items-center justify-center border-r-2 border-black border-l-4 border-l-brand p-6">
                                <span class="text-5xl font-black leading-none mb-1">69</span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">RECEPTEN</span>
                            </div>
                            <div class="flex flex-col items-center justify-center border-l-4 border-l-brand p-6">
                                <span class="text-5xl font-black leading-none mb-1">12</span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">REVIEWS</span>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ============================================================
                     TAB NAV
                ============================================================ --}}
                <div class="flex gap-3 mb-8">
                    <button class="flex items-center gap-2 bg-brand text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000]">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        FAVORIETEN
                    </button>
                    <button class="flex items-center gap-2 bg-white text-black text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:bg-[var(--pink-soft)] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        MIJN REVIEWS
                    </button>
                    <button class="flex items-center gap-2 bg-white text-black text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:bg-[var(--pink-soft)] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        INSTELLINGEN
                    </button>
                </div>

                {{-- ============================================================
                     FAVORIETEN SECTION
                ============================================================ --}}
                <h2 class="text-sm font-black uppercase tracking-widest mb-5">JE BEST BEWAARDE GEHEIMEN</h2>

                @php
                    $favorieten = [
                        [
                            'title'  => 'GEZONDE POKÉ BOWL VAN DE ALDI',
                            'time'   => '15 MIN',
                            'afwas'  => 'AFWAS-SCORE: LAAG',
                            'price'  => '€3.50 P.P.',
                            'img'    => 'brakke_bowl',
                        ],
                        [
                            'title'  => 'THE ULTIMATE HANGOVER BURGER',
                            'time'   => '25 MIN',
                            'afwas'  => 'AFWAS-SCORE: HOOG',
                            'price'  => '€4.50 P.P.',
                            'img'    => 'budget_nachos',
                        ],
                        [
                            'title'  => 'SPICY VODKA PASTA ZONDER VODKA',
                            'time'   => '12 MIN',
                            'afwas'  => 'AFWAS-SCORE: GEMIDDELD',
                            'price'  => '€2.50 P.P.',
                            'img'    => 'kater_pasta',
                        ],
                        [
                            'title'  => 'DE "IK HEB NOG 3 EURO" OMELET',
                            'time'   => '5 MIN',
                            'afwas'  => 'AFWAS-SCORE: LAAG',
                            'price'  => '€1.80 P.P.',
                            'img'    => 'kater_bowl',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-2 gap-4 mb-8">
                    @foreach($favorieten as $fav)
                        <div class="relative border-2 border-black shadow-[4px_4px_0px_0px_#000] overflow-hidden bg-black cursor-pointer group">

                            {{-- Image --}}
                            <div class="relative overflow-hidden" style="aspect-ratio:4/3;">
                                <img src="{{ asset('images/' . $fav['img'] . '.png') }}"
                                     alt="{{ $fav['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                {{-- Dark gradient overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                                {{-- FAVORIET badge top-left --}}
                                <span class="absolute top-3 left-3 bg-[var(--lime)] text-black text-[8px] font-black uppercase tracking-widest px-2 py-1 border border-black">
                                    FAVORIET
                                </span>

                                {{-- Price badge top-right --}}
                                <span class="absolute top-3 right-3 bg-brand text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 border border-black">
                                    {{ $fav['price'] }}
                                </span>

                                {{-- Text overlay bottom --}}
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="text-white font-black uppercase text-sm leading-snug mb-2">{{ $fav['title'] }}</h3>
                                    <div class="flex items-center gap-4">
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
                                            </svg>
                                            {{ $fav['time'] }}
                                        </span>
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            {{ $fav['afwas'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Show all button --}}
                <div class="flex justify-center mb-4">
                    <button class="text-[11px] font-black uppercase tracking-widest px-14 py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                        + TOON ALLE 69 FAVORIETEN
                    </button>
                </div>

            </div>
        </main>

        <x-footer />

    </body>
</html>
