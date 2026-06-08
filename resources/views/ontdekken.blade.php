<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ontdekken - Hapklaar</title>
        <meta name="description" content="Doorzoek ons receptenaanbod op dieet, bereidingstijd en meer. Vind je perfecte recept op Hapklaar.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/ontdekken') }}">
        <meta property="og:title" content="Ontdekken - Hapklaar">
        <meta property="og:description" content="Doorzoek ons receptenaanbod op dieet, bereidingstijd en meer.">
        <meta property="og:image" content="{{ asset('icons/icon-512.png') }}">
        <meta name="twitter:card" content="summary_large_image">
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-6 md:py-8">
            <div>
                <livewire:recipe-grid />


            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
