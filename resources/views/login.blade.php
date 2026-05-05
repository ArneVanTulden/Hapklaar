<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inloggen - Hapklaar</title>

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

            {{-- Cutlery icon top-left --}}
            <a href="{{ route('home') }}" class="absolute top-6 left-6">
                <div class="w-9 h-9 bg-black flex items-center justify-center hover:bg-gray-900 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
            </a>

            {{-- Main content --}}
            <div class="flex flex-col justify-center flex-1 px-14 pb-20">

                {{-- Logo badge --}}
                <div class="inline-block mb-8">
                    <div class="bg-black px-6 py-4 inline-block">
                        <span class="text-[3.2rem] font-black uppercase italic text-white leading-none tracking-tight">HAPKLAAR</span>
                    </div>
                </div>

                {{-- Tagline --}}
                <h2 class="text-[3rem] font-black uppercase italic text-white leading-[1.05]">
                    JE MAALTIJD<br>WACHT.<br>LOG IN EN<br>KOOK.
                </h2>
            </div>

            {{-- Hamburger illustration bottom-right --}}
            <div class="absolute bottom-0 right-0 opacity-30 pointer-events-none"
                 style="transform: translate(15%, 10%);">
                <svg viewBox="0 0 200 160" width="320" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Bun top --}}
                    <ellipse cx="100" cy="50" rx="85" ry="45" fill="white" opacity="0.9"/>
                    {{-- Patty --}}
                    <rect x="20" y="90" width="160" height="22" rx="4" fill="white" opacity="0.7"/>
                    {{-- Wavy lines (sauce/lettuce) --}}
                    <path d="M20 82 Q40 74 60 82 Q80 90 100 82 Q120 74 140 82 Q160 90 180 82" stroke="white" stroke-width="8" stroke-linecap="round" fill="none" opacity="0.6"/>
                    {{-- Bun bottom --}}
                    <rect x="15" y="112" width="170" height="28" rx="14" fill="white" opacity="0.85"/>
                </svg>
            </div>

        </div>

        {{-- ============================================================
             RIGHT PANEL
        ============================================================ --}}
        <div class="w-1/2 bg-[var(--pink-soft)] flex items-center justify-center">

            <div class="w-full max-w-md bg-white border-2 border-black shadow-[8px_8px_0px_0px_#000] p-10 mx-8">

                <h1 class="text-4xl font-black uppercase mb-8 leading-none">INLOGGEN</h1>

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

                    {{-- Password --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest mb-2">WACHTWOORD</label>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   placeholder="••••••••"
                                   class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors pr-12">
                            <button type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-brand text-white text-[13px] font-black uppercase italic tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        INLOGGEN →
                    </button>

                </form>

                {{-- Forgot password --}}
                <div class="text-center mt-5">
                    <a href="#" class="text-sm font-medium underline underline-offset-2 text-black hover:text-brand transition-colors">
                        Wachtwoord vergeten?
                    </a>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-4 my-5">
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <span class="text-xs font-bold text-gray-400">— OF —</span>
                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                {{-- Register --}}
                <a href="#"
                   class="block w-full text-center text-[11px] font-black uppercase tracking-widest py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 no-underline text-black">
                    MAAK EEN ACCOUNT AAN
                </a>

            </div>

        </div>

    </body>
</html>
