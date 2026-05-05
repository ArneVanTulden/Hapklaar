<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wachtwoord Vergeten - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 h-screen overflow-hidden flex">

        {{-- ============================================================
             LEFT PANEL
        ============================================================ --}}
        <div class="w-1/2 bg-brand flex flex-col relative overflow-hidden">

            {{-- Back button --}}
            <a href="{{ route('home') }}" class="absolute top-6 left-6">
                <div class="w-9 h-9 bg-black flex items-center justify-center hover:bg-gray-900 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
            </a>

            {{-- Main content --}}
            <div class="flex flex-col justify-center flex-1 px-14 pb-32">

                {{-- Logo badge --}}
                <div class="inline-block mb-8">
                    <div class="bg-black px-6 py-4 inline-block">
                        <span class="text-[3.2rem] font-black uppercase italic text-white leading-none tracking-tight">HAPKLAAR</span>
                    </div>
                </div>

                {{-- Tagline --}}
                <h2 class="text-[3rem] font-black uppercase italic text-white leading-[1.05]">
                    JE<br>WACHTWOORD?<br>KWIJT.<br>JE HONGER?<br>NOG NIET.
                </h2>
            </div>

            {{-- Hamburger illustration bottom-left --}}
            <div class="absolute bottom-0 left-0 pointer-events-none"
                 style="transform: translate(-10%, 15%);">
                <svg viewBox="0 0 200 160" width="280" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="50" rx="85" ry="45" fill="#B0004E" opacity="0.9"/>
                    <rect x="20" y="90" width="160" height="22" rx="4" fill="#B0004E" opacity="0.8"/>
                    <path d="M20 82 Q40 74 60 82 Q80 90 100 82 Q120 74 140 82 Q160 90 180 82" stroke="#B0004E" stroke-width="8" stroke-linecap="round" fill="none" opacity="0.9"/>
                    <rect x="15" y="112" width="170" height="28" rx="14" fill="#B0004E" opacity="0.85"/>
                </svg>
            </div>

        </div>

        {{-- ============================================================
             RIGHT PANEL
        ============================================================ --}}
        <div class="w-1/2 bg-[var(--pink-soft)] flex items-center justify-center">

            <div class="w-full max-w-md bg-white border-2 border-black shadow-[8px_8px_0px_0px_#000] p-10 mx-8">

                <h1 class="text-4xl font-black uppercase mb-4 leading-tight">WACHTWOORD<br>VERGETEN?</h1>

                <p class="text-sm text-gray-500 mb-7 leading-relaxed">
                    Geen stress. Vul je e-mailadres in en we sturen je een link om een nieuw wachtwoord aan te maken.
                </p>

                <form method="POST" action="#" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2">E-MAILADRES</label>
                        <input type="email"
                               name="email"
                               placeholder="jouw@email.nl"
                               class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors">
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-brand text-white text-[13px] font-black uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        STUUR LINK &nbsp;→
                    </button>

                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-gray-400"></div>
                    <span class="text-xs font-bold text-gray-500">OF</span>
                    <div class="flex-1 h-px bg-gray-400"></div>
                </div>

                {{-- Back to login --}}
                <a href="{{ route('login') }}"
                   class="block w-full text-center text-[11px] font-black uppercase tracking-widest py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                    TERUG NAAR INLOGGEN
                </a>

            </div>

        </div>

    </body>
</html>
