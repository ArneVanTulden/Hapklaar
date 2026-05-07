<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ontdekken - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-8">
            <div class="max-w-6xl mx-auto px-6 flex gap-7 items-start">

                {{-- ============================================================
                     FILTER SIDEBAR
                ============================================================ --}}
                <aside class="w-64 flex-shrink-0 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-6">

                    <h2 class="text-4xl font-black uppercase italic mb-6 leading-none">FILTERS</h2>

                    {{-- Dieetwensen --}}
                    <div class="mb-6">
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">DIEETWENSEN</p>
                        <div class="space-y-3">
                            @php
                                $diets = [
                                    ['label' => 'VEGETARISCH', 'checked' => false],
                                    ['label' => 'HALAL',       'checked' => false],
                                    ['label' => 'LACTOSEVRIJ', 'checked' => true],
                                    ['label' => 'GLUTENVRIJ',  'checked' => false],
                                ];
                            @endphp
                            @foreach($diets as $diet)
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <span class="w-5 h-5 border-2 border-black flex items-center justify-center flex-shrink-0 bg-white">
                                        @if($diet['checked'])
                                            <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M2 6l3 3 5-5"/></svg>
                                        @endif
                                    </span>
                                    <span class="text-[11px] font-bold uppercase tracking-wide">{{ $diet['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Calorieën --}}
                    <div class="mb-6">
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">CALORIEËN</p>
                        <input type="range" min="0" max="2000" value="800"
                               class="w-full h-1.5 accent-black cursor-pointer mb-2.5">
                        <span class="inline-block bg-[var(--lime)] border-2 border-black px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">MAX 800 KCAL</span>
                    </div>

                    {{-- Afwas-score --}}
                    <div class="mb-7">
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">MAX AFWAS-SCORE</p>
                        <div class="flex gap-1.5">
                            @for($i = 1; $i <= 5; $i++)
                                <button class="w-9 h-9 border-2 border-black font-black text-sm transition-colors {{ $i === 1 ? 'bg-[var(--lime)]' : 'bg-white hover:bg-[var(--pink-soft)]' }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>

                    {{-- Reset --}}
                    <button class="w-full bg-brand text-white text-[11px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        FILTERS WISSEN
                    </button>

                </aside>

                {{-- ============================================================
                     MAIN CONTENT
                ============================================================ --}}
                <div class="flex-1 min-w-0">

                    {{-- Page header --}}
                    <div class="flex items-start justify-between mb-1.5">
                        <h1 class="text-5xl font-black uppercase italic leading-[1.05]">
                            POPULAIRE<br>RECEPTEN
                        </h1>
                        <div class="bg-[var(--lime)] border-2 border-black shadow-[3px_3px_0px_0px_#000] px-4 py-2 text-center">
                            <div class="text-lg font-black leading-none">128</div>
                            <div class="text-[9px] font-black uppercase tracking-widest leading-none mt-0.5">RESULTATEN</div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-5">Ontdek de favorieten van studenten deze week.</p>

                    {{-- Tabs --}}
                    <div class="flex gap-7 border-b-2 border-black mb-6">
                        <button class="text-[11px] font-black uppercase tracking-widest pb-2.5 border-b-2 border-brand text-brand -mb-px">POPULAIR</button>
                        <button class="text-[11px] font-black uppercase tracking-widest pb-2.5 text-gray-400 hover:text-black transition-colors -mb-px">SNEL</button>
                        <button class="text-[11px] font-black uppercase tracking-widest pb-2.5 text-gray-400 hover:text-black transition-colors -mb-px">GOEDKOOP</button>
                    </div>

                    {{-- Recipe cards --}}
                    @php
                        $cards = [
                            [
                                'badge'          => 'STUDENT FAVORIET',
                                'badge_class'    => 'bg-brand text-white',
                                'title'          => 'ULTIEME KATER PASTA',
                                'price'          => '€1.50',
                                'time'           => '15 MIN',
                                'kcal'           => '650 KCAL',
                                'tag'            => 'LACTOSEVRIJ',
                                'rating'         => '4.8',
                                'img'            => 'kater_pasta',
                                'avatars'        => ['bg-purple-500', 'bg-indigo-400', 'bg-teal-400'],
                                'extra_users'    => 12,
                                'slug'           => 'ultieme-kater-pasta',
                            ],
                            [
                                'badge'          => 'GEZONDE KEUZE',
                                'badge_class'    => 'bg-[var(--lime)] text-black',
                                'title'          => 'STUDEREN SALADE',
                                'price'          => '€2.10',
                                'time'           => '10 MIN',
                                'kcal'           => '420 KCAL',
                                'tag'            => 'HALAL',
                                'rating'         => '4.5',
                                'img'            => 'brakke_bowl',
                                'avatars'        => ['bg-purple-500', 'bg-blue-400'],
                                'extra_users'    => 4,
                                'slug'           => 'studeren-salade',
                            ],
                            [
                                'badge'          => 'SUPER GOEDKOOP',
                                'badge_class'    => 'bg-[var(--lime)] text-black',
                                'title'          => 'CHEEKY BURGER',
                                'price'          => '€3.50',
                                'time'           => '25 MIN',
                                'kcal'           => '890 KCAL',
                                'tag'            => 'WEEKEND',
                                'rating'         => '5.0',
                                'img'            => 'budget_nachos',
                                'avatars'        => ['bg-purple-500', 'bg-orange-400'],
                                'extra_users'    => 42,
                                'slug'           => 'cheeky-burger',
                            ],
                            [
                                'badge'          => 'COMFORT FOOD',
                                'badge_class'    => 'bg-brand text-white',
                                'title'          => 'HONEY PIZZA',
                                'price'          => '€2.80',
                                'time'           => '45 MIN',
                                'kcal'           => '780 KCAL',
                                'tag'            => 'VEGETARISCH',
                                'rating'         => '4.7',
                                'img'            => 'kater_bowl',
                                'avatars'        => ['bg-purple-500', 'bg-blue-400'],
                                'extra_users'    => 8,
                                'slug'           => 'honey-pizza',
                            ],
                            [
                                'badge'          => 'ULTRA SNEL',
                                'badge_class'    => 'bg-[var(--lime)] text-black',
                                'title'          => 'GEBAKKEN NOODLES',
                                'price'          => '€0.90',
                                'time'           => '5 MIN',
                                'kcal'           => '310 KCAL',
                                'tag'            => 'GLUTENVRIJ',
                                'rating'         => '4.2',
                                'img'            => 'brakke_bowl',
                                'avatars'        => ['bg-indigo-500'],
                                'extra_users'    => 0,
                                'slug'           => 'gebakken-noodles',
                            ],
                            [
                                'badge'          => 'VEGA WINNER',
                                'badge_class'    => 'bg-[var(--lime)] text-black',
                                'title'          => 'POMPOEN SOEP',
                                'price'          => '€1.20',
                                'time'           => '20 MIN',
                                'kcal'           => '410 KCAL',
                                'tag'            => 'VEGAN',
                                'rating'         => '4.9',
                                'img'            => 'kater_bowl',
                                'avatars'        => ['bg-purple-500', 'bg-teal-500'],
                                'extra_users'    => 0,
                                'slug'           => 'pompoen-soep',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-3 gap-5">
                        @foreach($cards as $card)
                            <a href="{{ route('recept', $card['slug']) }}"
                               class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] flex flex-col no-underline text-inherit hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">

                                {{-- Image --}}
                                <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                                    <img src="{{ asset('images/' . $card['img'] . '.png') }}"
                                         alt="{{ $card['title'] }}"
                                         class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 text-[8px] font-black uppercase tracking-widest px-2 py-1 {{ $card['badge_class'] }}">
                                        {{ $card['badge'] }}
                                    </span>
                                    <button class="absolute top-2 right-2 w-7 h-7 bg-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000] hover:bg-[var(--pink-soft)] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Card body --}}
                                <div class="p-4 flex flex-col flex-1">

                                    {{-- Title + price --}}
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h3 class="font-black uppercase text-sm leading-snug">{{ $card['title'] }}</h3>
                                        <span class="text-brand font-black text-sm flex-shrink-0">{{ $card['price'] }}</span>
                                    </div>

                                    <div class="border-t border-gray-200 mb-2"></div>

                                    {{-- Meta --}}
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/>
                                                <path stroke-linecap="round" d="M12 6v6l4 2"/>
                                            </svg>
                                            {{ $card['time'] }}
                                        </span>
                                        <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            {{ $card['kcal'] }}
                                        </span>
                                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border border-black">{{ $card['tag'] }}</span>
                                    </div>

                                    <div class="border-t border-gray-200 mb-2"></div>

                                    {{-- Avatars + rating --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <div class="flex -space-x-1.5">
                                                @foreach($card['avatars'] as $color)
                                                    <div class="w-6 h-6 rounded-full {{ $color }} border-2 border-white"></div>
                                                @endforeach
                                            </div>
                                            @if($card['extra_users'] > 0)
                                                <span class="text-[8px] font-black bg-[var(--lime)] border border-black px-1 py-0.5 leading-none">+{{ $card['extra_users'] }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] font-black">★ {{ $card['rating'] }}</span>
                                    </div>

                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Load more --}}
                    <div class="flex justify-center mt-10 mb-4">
                        <button class="text-[11px] font-black uppercase tracking-widest px-16 py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_var(--pink)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_var(--pink)] transition-all duration-75">
                            MEER <span class="font-normal">RECEPTEN LADEN</span>
                        </button>
                    </div>

                </div>

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
