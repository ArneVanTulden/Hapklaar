<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Privacybeleid - Hapklaar</title>
        <meta name="description" content="Hoe Hapklaar omgaat met jouw persoonsgegevens.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-10 sm:py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6">

                {{-- Header --}}
                <div class="mb-10">
                    <h1 class="text-4xl sm:text-6xl font-black uppercase italic leading-none mb-3">PRIVACYBELEID</h1>
                    <p class="text-brand font-black uppercase italic text-sm sm:text-base leading-none">HAPKLAAR — LAATST BIJGEWERKT {{ date('d F Y') }}</p>
                </div>

                {{-- Content card --}}
                <div class="bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000] p-7 sm:p-10 space-y-10">

                    {{-- 1. Wie zijn wij --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">1. WIE ZIJN WIJ</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Hapklaar is een Belgisch receptenplatform dat je helpt koken met wat je in huis hebt.
                            Verantwoordelijk voor de verwerking van jouw gegevens:
                        </p>
                        <div class="mt-3 bg-[var(--pink-soft)] border-2 border-black p-4 text-sm font-bold space-y-1">
                            <p>HAPKLAAR</p>
                            <p>België</p>
                            <p>E-mail: <a href="mailto:info@hapklaar.net" class="underline hover:text-brand">info@hapklaar.net</a></p>
                        </div>
                    </section>

                    {{-- 2. Welke gegevens --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">2. WELKE GEGEVENS VERZAMELEN WIJ</h2>
                        <ul class="text-sm leading-relaxed text-gray-700 space-y-2 list-none p-0">
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Accountgegevens:</strong> naam en e-mailadres die je invult bij registratie.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Gebruiksdata:</strong> je favoriete recepten, boodschappenlijst en voorraad die je zelf aanmaakt.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Technische gegevens:</strong> IP-adres en browser bij inloggen, enkel voor beveiliging.</span></li>
                        </ul>
                        <p class="text-sm text-gray-700 mt-3">Wij gebruiken <strong>geen</strong> advertising trackers of analytics van derden.</p>
                    </section>

                    {{-- 3. Waarom --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">3. WAAROM VERWERKEN WIJ JE GEGEVENS</h2>
                        <ul class="text-sm leading-relaxed text-gray-700 space-y-2 list-none p-0">
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>Om jouw account te beheren en je te laten inloggen.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>Om je boodschappenlijst, voorraad en favorieten op te slaan.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>Om wachtwoordherstel mogelijk te maken via e-mail.</span></li>
                        </ul>
                        <p class="text-sm text-gray-700 mt-3">Rechtsgrond: uitvoering van de overeenkomst (art. 6.1.b GDPR).</p>
                    </section>

                    {{-- 4. Bewaring --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">4. HOE LANG BEWAREN WIJ JE GEGEVENS</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Jouw gegevens worden bewaard zolang je account actief is. Na verwijdering van je account worden alle persoonsgegevens binnen 30 dagen gewist.
                        </p>
                    </section>

                    {{-- 5. Derden --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">5. DELEN MET DERDEN</h2>
                        <p class="text-sm leading-relaxed text-gray-700 mb-3">
                            Wij verkopen jouw gegevens nooit. We maken gebruik van de volgende verwerkers:
                        </p>
                        <ul class="text-sm leading-relaxed text-gray-700 space-y-2 list-none p-0">
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Hostingprovider:</strong> voor opslag van de applicatie en database.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>E-mailservice:</strong> voor het versturen van wachtwoordherstel e-mails.</span></li>
                        </ul>
                        <p class="text-sm text-gray-700 mt-3">Alle verwerkers zijn contractueel verplicht jouw data te beschermen conform de GDPR.</p>
                    </section>

                    {{-- 6. Jouw rechten --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">6. JOUW RECHTEN</h2>
                        <p class="text-sm leading-relaxed text-gray-700 mb-3">Als betrokkene heb je recht op:</p>
                        <ul class="text-sm leading-relaxed text-gray-700 space-y-2 list-none p-0">
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Inzage</strong> — opvragen welke gegevens wij van je bewaren.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Correctie</strong> — foutieve gegevens laten aanpassen.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Verwijdering</strong> — je account en alle gegevens laten wissen.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span><strong>Bezwaar</strong> — verzet aantekenen tegen verwerking.</span></li>
                        </ul>
                        <p class="text-sm text-gray-700 mt-3">
                            Stuur een e-mail naar <a href="mailto:info@hapklaar.net" class="font-bold underline hover:text-brand">info@hapklaar.net</a> om een recht uit te oefenen. We reageren binnen 30 dagen.
                        </p>
                    </section>

                    {{-- 7. Klachten --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">7. KLACHT INDIENEN</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Ben je niet tevreden over hoe wij met jouw gegevens omgaan? Je kan een klacht indienen bij de
                            <strong>Gegevensbeschermingsautoriteit (GBA)</strong>:<br>
                            <a href="https://www.gegevensbeschermingsautoriteit.be" target="_blank" rel="noopener" class="font-bold underline hover:text-brand">www.gegevensbeschermingsautoriteit.be</a>
                        </p>
                    </section>

                </div>

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
