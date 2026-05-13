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
                @php
                    $dietOptions      = [
                        ['label' => 'VEGETARISCH', 'value' => 'Vegetarisch'],
                        ['label' => 'HALAL',       'value' => 'Halal'],
                        ['label' => 'LACTOSEVRIJ', 'value' => 'Lactosevrij'],
                        ['label' => 'GLUTENVRIJ',  'value' => 'Glutenvrij'],
                    ];
                    $activeDiets      = request('diets', []);
                    $activeMaxCal     = request('max_calories', 1000);
                    $activeMaxAfwas   = request()->has('max_afwas') ? (int) request('max_afwas') : null;
                @endphp

                <aside
                    x-data="{
                        diets:    {{ json_encode($activeDiets) }},
                        maxCal:   {{ (int) $activeMaxCal }},
                        maxAfwas: {{ $activeMaxAfwas ?? 'null' }},
                        submit()      { this.$nextTick(() => this.$refs.form.submit()); },
                        toggleDiet(v) {
                            const i = this.diets.indexOf(v);
                            i === -1 ? this.diets.push(v) : this.diets.splice(i, 1);
                            this.submit();
                        },
                        setAfwas(v)   { this.maxAfwas = (this.maxAfwas === v) ? null : v; this.submit(); }
                    }"
                    class="w-64 flex-shrink-0 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-6"
                >
                    <h2 class="text-4xl font-black uppercase italic mb-6 leading-none">FILTERS</h2>

                    <form x-ref="form" method="GET" action="{{ route('ontdekken') }}">
                        <template x-for="d in diets" :key="d">
                            <input type="hidden" name="diets[]" :value="d">
                        </template>
                        <input type="hidden" name="max_calories" :value="maxCal">
                        <template x-if="maxAfwas !== null">
                            <input type="hidden" name="max_afwas" :value="maxAfwas">
                        </template>

                        {{-- Dieetwensen --}}
                        <div class="mb-6">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">DIEETWENSEN</p>
                            <div class="space-y-3">
                                @foreach($dietOptions as $diet)
                                    <label @click.prevent="toggleDiet('{{ $diet['value'] }}')" class="flex items-center gap-3 cursor-pointer select-none">
                                        <span class="w-5 h-5 border-2 border-black flex items-center justify-center flex-shrink-0 bg-white">
                                            <svg x-show="diets.includes('{{ $diet['value'] }}')" class="w-3 h-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                <path d="M2 6l3 3 5-5"/>
                                            </svg>
                                        </span>
                                        <span class="text-[11px] font-bold uppercase tracking-wide">{{ $diet['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Calorieën --}}
                        <div class="mb-6">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">CALORIEËN</p>
                            <input type="range" min="0" max="1000" step="25"
                                   x-model="maxCal"
                                   @change="submit()"
                                   class="w-full h-1.5 accent-black cursor-pointer mb-2.5">
                            <span class="inline-block bg-[var(--lime)] border-2 border-black px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">
                                MAX <span x-text="maxCal"></span> KCAL
                            </span>
                        </div>

                        {{-- Afwas-score --}}
                        <div class="mb-7">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-3">MAX AFWAS-SCORE</p>
                            <div class="flex gap-1.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            @click="setAfwas({{ $i }})"
                                            :class="maxAfwas !== null && {{ $i }} <= maxAfwas ? 'bg-[var(--lime)]' : 'bg-white hover:bg-[var(--pink-soft)]'"
                                            class="w-9 h-9 border-2 border-black font-black text-sm transition-colors">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                        </div>

                        {{-- Reset --}}
                        <a href="{{ route('ontdekken') }}"
                           class="block w-full text-center bg-brand text-white text-[11px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                            FILTERS WISSEN
                        </a>
                    </form>

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
                            <div class="text-lg font-black leading-none">{{ $recipes->count() }}</div>
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
                        $badgeCycle  = ['bg-brand text-white', 'bg-purple-600 text-white', 'bg-[var(--lime)] text-black'];
                        $avatarCycle = ['bg-purple-500', 'bg-indigo-400', 'bg-teal-400', 'bg-orange-400', 'bg-pink-500', 'bg-blue-400'];
                    @endphp

                    <div x-data="{ expanded: false }">

                    <div class="grid grid-cols-3 gap-5">
                        @forelse($recipes as $i => $recipe)
                            @php
                                $tag         = $recipe->dietTags->first()?->name;
                                $badgeClass  = $badgeCycle[$i % 3];
                                $avatarColor = $avatarCycle[($recipe->created_by ?? 0) % count($avatarCycle)];
                                $initial     = strtoupper(substr($recipe->creator?->username ?? '?', 0, 1));
                            @endphp
                            <a data-recipe-card
                               href="{{ route('recept', $recipe->id) }}"
                               @if($i >= 6) x-show="expanded" style="display:none" @endif
                               class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] flex flex-col no-underline text-inherit hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">

                                {{-- Image --}}
                                <div class="relative border-b-2 border-black" style="aspect-ratio:4/3;">
                                    <div class="overflow-hidden w-full h-full absolute inset-0">
                                        @if($recipe->image_path)
                                            <img src="{{ asset('storage/' . $recipe->image_path) }}"
                                                 alt="{{ $recipe->title }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 21h18M3.75 3h16.5M5.25 21V6.75A2.25 2.25 0 017.5 4.5h9a2.25 2.25 0 012.25 2.25V21"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    @if($recipe->badge)
                                        <span class="absolute top-3 -left-[2px] z-10 text-[8px] font-black uppercase tracking-widest px-3 py-1.5 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $badgeClass }}">
                                            {{ strtoupper($recipe->badge) }}
                                        </span>
                                    @endif
                                    <button class="absolute top-2 right-2 z-10 w-7 h-7 bg-white border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000] hover:bg-[var(--pink-soft)] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Card body --}}
                                <div class="p-4 flex flex-col flex-1">

                                    {{-- Title --}}
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h3 class="font-black uppercase text-sm leading-snug">{{ strtoupper($recipe->title) }}</h3>
                                    </div>

                                    <div class="border-t border-gray-200 mb-2"></div>

                                    {{-- Meta --}}
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        @if($recipe->prep_time_minutes)
                                            <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path stroke-linecap="round" d="M12 6v6l4 2"/>
                                                </svg>
                                                {{ $recipe->prep_time_minutes }} MIN
                                            </span>
                                        @endif
                                        @if($recipe->calories_per_portion)
                                            <span class="flex items-center gap-1 text-[9px] font-bold uppercase text-gray-500">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                {{ round($recipe->calories_per_portion) }} KCAL
                                            </span>
                                        @endif
                                        @if($tag)
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border border-black">{{ strtoupper($tag) }}</span>
                                        @endif
                                    </div>

                                    <div class="border-t border-gray-200 mb-2"></div>

                                    {{-- Avatar + rating --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full {{ $avatarColor }} border-2 border-white flex items-center justify-center">
                                                <span class="text-[8px] font-black text-white leading-none">{{ $initial }}</span>
                                            </div>
                                            @if($recipe->review_count > 0)
                                                <span class="text-[8px] font-black bg-[var(--lime)] border border-black px-1 py-0.5 leading-none">+{{ $recipe->review_count }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] font-black">★ {{ number_format($recipe->avg_rating, 1) }}</span>
                                    </div>

                                </div>
                            </a>
                        @empty
                            <div class="col-span-3 py-10 flex flex-col items-center gap-3 border-2 border-dashed border-gray-300">
                                <p class="text-lg font-black uppercase leading-none">Geen recepten gevonden</p>
                                <p class="text-xs text-gray-400">Pas de filters aan of wis ze om meer te zien.</p>
                                <a href="{{ route('ontdekken') }}"
                                   class="mt-1 text-[11px] font-black uppercase tracking-widest px-6 py-3 border-2 border-black bg-[var(--lime)] shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    FILTERS WISSEN
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Load more / collapse --}}
                    @if($recipes->count() > 6)
                    <div class="flex justify-center mt-10 mb-4">
                        <button type="button"
                                @click="expanded = !expanded"
                                class="text-[11px] font-black uppercase tracking-widest px-16 py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_var(--pink)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_var(--pink)] transition-all duration-75">
                            <span x-show="!expanded">MEER <span class="font-normal">RECEPTEN LADEN</span></span>
                            <span x-show="expanded" style="display:none">MINDER <span class="font-normal">RECEPTEN TONEN</span></span>
                        </button>
                    </div>
                    @endif

                    </div>{{-- /x-data wrapper --}}

                </div>

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
