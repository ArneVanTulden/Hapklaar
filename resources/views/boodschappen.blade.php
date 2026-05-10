<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Boodschappen - Hapklaar</title>

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
                <div class="relative mb-8">

                    {{-- GEEN STRESS badge top-right --}}
                    <div class="absolute top-0 right-0 bg-[var(--lime)] border-2 border-black px-4 py-2 text-[10px] font-black uppercase tracking-widest shadow-[3px_3px_0px_0px_#000]"
                         style="transform: rotate(-2deg);">
                        GEEN STRESS
                    </div>

                    {{-- Heading box --}}
                    <div class="inline-block bg-black border-b-4 border-brand pr-6 py-4 pl-4 mb-5">
                        <h1 class="text-[4rem] font-black uppercase italic leading-none text-white tracking-tight">
                            BOODSC<span class="italic">HAPPEN</span>
                        </h1>
                    </div>

                    <p class="font-black uppercase text-sm tracking-wide leading-snug max-w-md">
                        ALLES WAT JE NODIG HEBT VOOR EEN LEGENDARISCHE WEEK.
                    </p>
                </div>

                {{-- ============================================================
                     TWO-COLUMN LAYOUT
                ============================================================ --}}
                <div class="flex gap-6 items-start">

                    {{-- ---- LEFT: Shopping list ---- --}}
                    <div class="flex-1 min-w-0 space-y-4">

                        @php
                            $sections = [
                                [
                                    'label'     => 'VERSE GROENTEN',
                                    'label_cls' => 'bg-[var(--lime)] text-black border-black',
                                    'items'     => [
                                        ['name' => '3X AVOCADO\'S',      'price' => '€4,50', 'checked' => true],
                                        ['name' => 'ZAK SPINAZIE (300G)', 'price' => '€1,89', 'checked' => false],
                                        ['name' => 'RODE PAPRIKA',        'price' => '€0,95', 'checked' => false],
                                    ],
                                ],
                                [
                                    'label'     => 'VLEES & VEGA',
                                    'label_cls' => 'bg-brand text-white border-brand',
                                    'items'     => [
                                        ['name' => 'KIPPENDIJEN (BIOLOGISCH)', 'price' => '€6,40', 'checked' => false],
                                        ['name' => 'VEGA GEHAKT',              'price' => '€3,25', 'checked' => false],
                                    ],
                                ],
                                [
                                    'label'     => 'VOORRAADKAST',
                                    'label_cls' => 'bg-purple-600 text-white border-purple-600',
                                    'items'     => [
                                        ['name' => 'PENNE PASTA (VOLKOREN)', 'price' => '€1,10', 'checked' => false],
                                        ['name' => 'BLIK TOMATENBLOKJES',    'price' => '€0,75', 'checked' => false],
                                    ],
                                ],
                            ];
                        @endphp

                        @foreach($sections as $section)
                            <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                                {{-- Section label --}}
                                <div class="inline-block mb-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 {{ $section['label_cls'] }}">
                                        {{ $section['label'] }}
                                    </span>
                                </div>

                                {{-- Items --}}
                                <div class="space-y-0">
                                    @foreach($section['items'] as $i => $item)
                                        <label class="flex items-center justify-between py-3 cursor-pointer group {{ $i < count($section['items']) - 1 ? 'border-b border-gray-200' : '' }}">
                                            <div class="flex items-center gap-3">
                                                {{-- Checkbox --}}
                                                <span class="w-5 h-5 border-2 border-black flex items-center justify-center flex-shrink-0 {{ $item['checked'] ? 'bg-[var(--lime)]' : 'bg-white group-hover:bg-[var(--pink-soft)]' }} transition-colors">
                                                    @if($item['checked'])
                                                        <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M2 6l3 3 5-5"/></svg>
                                                    @endif
                                                </span>
                                                <span class="text-[11px] font-bold uppercase tracking-wide {{ $item['checked'] ? 'line-through text-gray-400' : '' }}">
                                                    {{ $item['name'] }}
                                                </span>
                                            </div>
                                            <span class="text-[12px] font-black {{ $item['checked'] ? 'text-gray-400' : '' }}">{{ $item['price'] }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach

                        {{-- Add item button --}}
                        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                            <button x-data @click="$dispatch('open-ingredient-modal', { mode: 'shopping' })"
                                    class="w-full flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest text-gray-500 hover:bg-[var(--pink-soft)] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" d="M12 8v8M8 12h8"/>
                                </svg>
                                ITEM TOEVOEGEN
                            </button>
                        </div>

                    </div>

                    {{-- ---- RIGHT: Kassa sidebar ---- --}}
                    <div class="w-72 flex-shrink-0 space-y-4">

                        {{-- Kassa box --}}
                        <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_var(--hot-pink)] p-6">

                            <h2 class="text-xl font-black uppercase italic text-center mb-5">KASSA MOMENTJE</h2>

                            {{-- Subtotaal --}}
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">SUBTOTAAL</span>
                                <span class="text-[12px] font-black">€18,89</span>
                            </div>

                            {{-- Korting --}}
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-black uppercase tracking-widest text-brand">STUDENTENKORTING (15%)</span>
                                <span class="text-[12px] font-black text-brand">– €2,83</span>
                            </div>

                            {{-- Dashed divider --}}
                            <div class="border-t-2 border-dashed border-gray-300 mb-3"></div>

                            {{-- Totaal --}}
                            <div class="flex justify-between items-center mb-5">
                                <span class="text-base font-black uppercase">TOTAAL</span>
                                <span class="text-2xl font-black">€16,06</span>
                            </div>

                            {{-- Shop buttons --}}
                            <div class="space-y-2.5">
                                <button class="w-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    BEKIJK BIJ ALBERT HE<span class="italic">IJ</span>N
                                </button>
                                <button class="w-full bg-[var(--yellow)] text-black text-[10px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    BEKIJK BIJ COLRUYT
                                </button>
                            </div>

                            <p class="text-center text-[8px] font-bold uppercase tracking-widest text-gray-400 mt-4">
                                BEDANKT VOOR HET KOKEN BIJ HAPKLAAR
                            </p>

                        </div>

                        {{-- Budget hack box --}}
                        <div class="bg-[var(--lime)] border-2 border-black shadow-[4px_4px_0px_0px_#000] p-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-1">BUDGET HACK</p>
                                    <p class="text-[11px] font-bold leading-snug">Wissel kip voor kikkererwten en bespaar €2,40!</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </main>

        <x-footer />

        <livewire:ingredient-modal />
        @livewireScripts
    </body>
</html>
