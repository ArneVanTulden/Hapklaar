<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mijn Keuken - Hapklaar</title>

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
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h1 class="text-[3.5rem] font-black uppercase italic leading-none mb-2">IJSKAST SCANNER</h1>
                        <p class="text-brand font-black uppercase italic text-lg leading-none">WAT KAN IK NOG MAKEN MET DIE ZOOI?</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0 mt-1">
                        <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-[var(--lime)] border-2 border-black">AI POWERED</span>
                        <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-black text-white border-2 border-black">BETA</span>
                        <button class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                            TEST M UIT
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                     SCANNER + RESULTS
                ============================================================ --}}
                <div class="grid grid-cols-2 gap-6 mb-12">

                    {{-- Left: Upload box --}}
                    @livewire('ijskast-scanner')

                    {{-- Right: Detected ingredients --}}
                    @livewire('scanner-results')

                </div>

                {{-- ============================================================
                     JOUW MATCHES
                ============================================================ --}}
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <h2 class="text-4xl font-black uppercase">JOUW MATCHES</h2>
                        <span class="bg-[var(--lime)] border-2 border-black px-3 py-1 text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]">JACKPOT!</span>
                    </div>

                    @php
                        $matches = [
                            [
                                'pct'      => '94%',
                                'tag'      => 'GEZOND',
                                'tag_cls'  => 'bg-brand text-white',
                                'title'    => 'STUDENTEN BOWL MET KIP',
                                'time'     => '15 MIN',
                                'price'    => '€1.50 PP',
                                'missing'  => ['AVOCADO'],
                                'img'      => 'kater_bowl',
                                'shadow'   => 'shadow-[5px_5px_0px_0px_var(--hot-pink)]',
                            ],
                            [
                                'pct'      => '82%',
                                'tag'      => 'SNACK',
                                'tag_cls'  => 'bg-brand text-white',
                                'title'    => 'RESTJES TACOS DELUXE',
                                'time'     => '10 MIN',
                                'price'    => '€0.80 PP',
                                'missing'  => ['TORTILLAS', 'LIMOEN'],
                                'img'      => 'budget_nachos',
                                'shadow'   => 'shadow-[5px_5px_0px_0px_#7C3AED]',
                            ],
                            [
                                'pct'      => '76%',
                                'tag'      => 'VULLEND',
                                'tag_cls'  => 'bg-brand text-white',
                                'title'    => 'KATER PASTA #12',
                                'time'     => '20 MIN',
                                'price'    => '€1.20 PP',
                                'missing'  => ['PARMEZAAN', 'KNOFLOOK'],
                                'img'      => 'kater_pasta',
                                'shadow'   => 'shadow-[5px_5px_0px_0px_#7C3AED]',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-3 gap-5">
                        @foreach($matches as $match)
                            <div class="bg-white border-2 border-black {{ $match['shadow'] }} flex flex-col">

                                {{-- Image --}}
                                <div class="relative overflow-hidden border-b-2 border-black" style="aspect-ratio:4/3;">
                                    <img src="{{ asset('images/' . $match['img'] . '.png') }}"
                                         alt="{{ $match['title'] }}"
                                         class="w-full h-full object-cover">

                                    {{-- Match % badge --}}
                                    <div class="absolute top-3 right-3 w-12 h-12 rounded-full bg-[var(--lime)] border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                                        <span class="text-[11px] font-black leading-none">{{ $match['pct'] }}</span>
                                    </div>

                                    {{-- Category tag --}}
                                    <span class="absolute bottom-3 left-3 text-[8px] font-black uppercase tracking-widest px-2 py-1 {{ $match['tag_cls'] }}">
                                        {{ $match['tag'] }}
                                    </span>
                                </div>

                                {{-- Card body --}}
                                <div class="p-4 flex flex-col flex-1">
                                    <h3 class="font-black uppercase text-sm leading-snug mb-2">{{ $match['title'] }}</h3>

                                    {{-- Stats --}}
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
                                            </svg>
                                            {{ $match['time'] }}
                                        </span>
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $match['price'] }}
                                        </span>
                                    </div>

                                    {{-- Missing ingredients --}}
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @foreach($match['missing'] as $miss)
                                            <span class="flex items-center gap-1 text-[8px] font-black uppercase tracking-widest px-2 py-1 border border-black text-gray-600">
                                                <span class="text-brand font-black">✕</span> MIST: {{ $miss }}
                                            </span>
                                        @endforeach
                                    </div>

                                    {{-- CTA --}}
                                    <button class="mt-auto w-full bg-brand text-white text-[10px] font-black uppercase tracking-widest py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                        BEKIJK RECEPT →
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============================================================
                     CTA BAR
                ============================================================ --}}
                <div class="bg-[var(--lime)] border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] flex items-center justify-between px-8 py-7">
                    <div>
                        <p class="font-black uppercase text-lg leading-tight mb-1">GEEN ZIN IN DEZE OPTIES?</p>
                        <p class="text-[11px] text-gray-600 uppercase font-bold tracking-wide">WE HEBBEN NOG 142 ANDERE IDEEËN VOOR JE.</p>
                    </div>
                    <a href="{{ route('recept.random') }}" class="bg-black text-white text-[11px] font-black uppercase tracking-widest px-8 py-4 border-2 border-black hover:bg-gray-900 transition-colors flex-shrink-0">
                        DOE MAAR EEN GOK
                    </a>
                </div>

            </div>
        </main>

        @livewire('ingredient-modal')

        <x-footer />

        @livewireScripts
    </body>
</html>
