<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nieuw Wachtwoord - Hapklaar</title>
        <meta name="robots" content="noindex, nofollow">
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 min-h-screen flex flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- LEFT PANEL (desktop only) --}}
        <div class="hidden md:flex w-full md:w-1/2 bg-brand flex-col relative overflow-hidden">

            <a href="{{ route('home') }}" class="absolute top-6 left-6">
                <div class="w-9 h-9 bg-black flex items-center justify-center hover:bg-gray-900 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
            </a>

            <div class="flex flex-col justify-center flex-1 px-14 pb-32">
                <div class="inline-block mb-8">
                    <div class="bg-black px-6 py-4 inline-block">
                        <span class="text-[3.2rem] font-black uppercase italic text-white leading-none tracking-tight">HAPKLAAR</span>
                    </div>
                </div>

                <h2 class="text-[3rem] font-black uppercase italic text-white leading-[1.05]">
                    NIEUW<br>WACHTWOORD.<br>VERSE<br>START.
                </h2>
            </div>

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

        {{-- RIGHT PANEL --}}
        <div class="w-full md:w-1/2 bg-[var(--pink-soft)] flex items-center justify-center relative py-10 md:py-0 md:overflow-y-auto min-h-screen md:min-h-0">

            {{-- Mobile back arrow --}}
            <a href="{{ route('home') }}" class="md:hidden absolute top-4 left-4 z-10">
                <div class="w-9 h-9 bg-black flex items-center justify-center hover:bg-gray-900 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </div>
            </a>

            <div class="w-full max-w-md bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000] md:shadow-[8px_8px_0px_0px_#000] p-6 md:p-10 mx-4 md:mx-8">

                <h1 class="text-3xl md:text-4xl font-black uppercase mb-4 leading-tight">NIEUW<br>WACHTWOORD</h1>

                <p class="text-sm text-gray-500 mb-7 leading-relaxed">
                    Kies een nieuw wachtwoord van minimaal 8 tekens.
                </p>

                <livewire:auth.reset-password :token="$token" />

            </div>

        </div>

    </body>
</html>
