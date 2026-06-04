<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mijn Keuken - Hapklaar</title>
        <meta name="robots" content="noindex, nofollow">
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6">

                {{-- ============================================================
                     PAGE HEADER
                ============================================================ --}}
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6 sm:mb-8">
                    <div>
                        <h1 class="text-3xl sm:text-5xl md:text-[3.5rem] font-black uppercase italic leading-none mb-2">IJSKAST SCANNER</h1>
                        <p class="text-brand font-black uppercase italic text-sm sm:text-lg leading-none">WAT KAN IK NOG MAKEN MET DIE ZOOI?</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap md:flex-shrink-0 md:mt-1">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 mb-10 sm:mb-12">

                    {{-- Left: Upload box --}}
                    @livewire('ijskast-scanner')

                    {{-- Right: Detected ingredients --}}
                    @livewire('scanner-results')

                </div>

                {{-- ============================================================
                     JOUW MATCHES
                ============================================================ --}}
                @livewire('kitchen-matches')

                {{-- ============================================================
                     CTA BAR
                ============================================================ --}}
                <div class="bg-[var(--lime)] border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-8 py-5 sm:py-7">
                    <div>
                        <p class="font-black uppercase text-base sm:text-lg leading-tight mb-1">GEEN ZIN IN DEZE OPTIES?</p>
                        <p class="text-[11px] text-gray-600 uppercase font-bold tracking-wide">WE HEBBEN NOG {{ \App\Models\Recipe::count() }} ANDERE IDEEËN VOOR JE.</p>
                    </div>
                    <a href="{{ route('recept.random') }}" class="bg-black text-white text-[11px] font-black uppercase tracking-widest px-6 sm:px-8 py-3 sm:py-4 border-2 border-black hover:bg-gray-900 transition-colors text-center sm:flex-shrink-0">
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
