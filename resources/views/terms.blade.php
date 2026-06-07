<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gebruiksvoorwaarden - Hapklaar</title>
        <meta name="description" content="De gebruiksvoorwaarden van Hapklaar.">

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
                    <h1 class="text-3xl sm:text-6xl font-black uppercase italic leading-none mb-3 break-words">GEBRUIKSVOORWAARDEN</h1>
                    <p class="text-brand font-black uppercase italic text-sm sm:text-base leading-none">HAPKLAAR — LAATST BIJGEWERKT 7 JUNI 2026</p>
                </div>

                {{-- Content card --}}
                <div class="bg-white border-2 border-black shadow-[6px_6px_0px_0px_#000] p-7 sm:p-10 space-y-10">

                    {{-- 1. Wie zijn wij --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">1. WIE ZIJN WIJ</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Hapklaar is een Belgisch receptenplatform dat je helpt koken met wat je in huis hebt.
                        </p>
                        <div class="mt-3 bg-[var(--pink-soft)] border-2 border-black p-4 text-sm font-bold space-y-1">
                            <p>HAPKLAAR</p>
                            <p>België</p>
                            <p>E-mail: <a href="mailto:info@hapklaar.net" class="underline hover:text-brand">info@hapklaar.net</a></p>
                        </div>
                    </section>

                    {{-- 2. Gebruik van de app --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">2. GEBRUIK VAN DE APP</h2>
                        <p class="text-sm leading-relaxed text-gray-700 mb-3">Door Hapklaar te gebruiken ga je akkoord met deze voorwaarden. Je mag de app gebruiken voor persoonlijk, niet-commercieel gebruik.</p>
                        <p class="text-sm leading-relaxed text-gray-700 mb-2">Het is niet toegestaan om:</p>
                        <ul class="text-sm leading-relaxed text-gray-700 space-y-2 list-none p-0">
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>De app te gebruiken voor commerciële doeleinden zonder schriftelijke toestemming.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>Inhoud van de app te kopiëren, te verspreiden of te verkopen.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>De app te misbruiken, te hacken of de werking ervan te verstoren.</span></li>
                            <li class="flex gap-3"><span class="font-black text-brand mt-0.5">—</span><span>Valse of misleidende informatie op te geven bij registratie.</span></li>
                        </ul>
                    </section>

                    {{-- 3. Intellectueel eigendom --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">3. INTELLECTUEEL EIGENDOM</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Alle inhoud op Hapklaar — waaronder recepten, teksten, afbeeldingen, logo's en het ontwerp — is eigendom van Hapklaar of haar licentiegevers en is beschermd door het auteursrecht. Niets van deze inhoud mag worden gereproduceerd of gebruikt zonder uitdrukkelijke toestemming.
                        </p>
                    </section>

                    {{-- 4. Aansprakelijkheid --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">4. AANSPRAKELIJKHEID</h2>
                        <p class="text-sm leading-relaxed text-gray-700 mb-3">
                            Hapklaar streeft naar correcte en actuele recepten, maar kan niet garanderen dat alle informatie volledig, juist of geschikt is voor jouw situatie. Raadpleeg bij twijfel over allergenen of voedingswaarden altijd een specialist.
                        </p>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Hapklaar is niet aansprakelijk voor schade die voortvloeit uit het gebruik van de app, tenzij er sprake is van opzet of grove nalatigheid van onze kant.
                        </p>
                    </section>

                    {{-- 5. Account --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">5. JOUW ACCOUNT</h2>
                        <p class="text-sm leading-relaxed text-gray-700 mb-3">
                            Je bent zelf verantwoordelijk voor de beveiliging van je account en wachtwoord. Meld verdacht gebruik meteen via <a href="mailto:info@hapklaar.net" class="font-bold underline hover:text-brand">info@hapklaar.net</a>.
                        </p>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Hapklaar behoudt het recht om accounts te blokkeren of verwijderen bij misbruik of overtreding van deze voorwaarden.
                        </p>
                    </section>

                    {{-- 6. Wijzigingen --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">6. WIJZIGINGEN</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Hapklaar kan deze voorwaarden op elk moment aanpassen. Bij ingrijpende wijzigingen word je per e-mail op de hoogte gesteld. Voortgezet gebruik van de app na wijziging geldt als aanvaarding van de nieuwe voorwaarden.
                        </p>
                    </section>

                    {{-- 7. Toepasselijk recht --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">7. TOEPASSELIJK RECHT</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Deze voorwaarden zijn onderworpen aan het Belgisch recht. Geschillen worden voorgelegd aan de bevoegde rechtbanken in België.
                        </p>
                    </section>

                    {{-- 8. Contact --}}
                    <section>
                        <h2 class="text-lg font-black uppercase tracking-wide mb-3 pb-2 border-b-2 border-black">8. CONTACT</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Vragen over deze voorwaarden? Stuur een e-mail naar <a href="mailto:info@hapklaar.net" class="font-bold underline hover:text-brand">info@hapklaar.net</a>.
                        </p>
                    </section>

                </div>

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
