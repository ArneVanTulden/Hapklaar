<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ontdekken - Hapklaar</title>
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 py-6 md:py-8" x-data="{ filtersOpen: false }">
            <div class="max-w-6xl mx-auto px-4 md:px-6 flex flex-col md:flex-row gap-5 md:gap-7 md:items-start">

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
                    $activeMaxCal     = request('max_calories', 1500);
                    $activeMaxAfwas   = request()->has('max_afwas') ? (int) request('max_afwas') : null;
                    $activeFilterCount = count((array)$activeDiets)
                        + ((int)$activeMaxCal < 1000 ? 1 : 0)
                        + ($activeMaxAfwas !== null ? 1 : 0);
                @endphp

                {{-- Mobile filter trigger --}}
                <button type="button"
                        @click="filtersOpen = true"
                        class="md:hidden flex items-center justify-between w-full bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] px-4 py-3">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4"/>
                        </svg>
                        <span class="text-[12px] font-black uppercase tracking-widest">FILTERS</span>
                    </span>
                    @if($activeFilterCount > 0)
                        <span class="bg-[var(--lime)] border-2 border-black text-[10px] font-black px-2 py-0.5">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                {{-- Mobile backdrop --}}
                <div x-show="filtersOpen"
                     x-transition.opacity
                     @click="filtersOpen = false"
                     class="md:hidden fixed inset-0 bg-black/50 z-40"
                     style="display: none;"></div>

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
                    :class="filtersOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                    class="fixed md:sticky md:top-6 md:transform-none inset-y-0 left-0 z-50 w-80 max-w-[85vw] md:w-64 md:max-w-none flex-shrink-0 bg-white border-r-2 md:border-2 border-black md:shadow-[4px_4px_0px_0px_#000] p-6 overflow-y-auto md:overflow-visible transition-transform duration-200"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-4xl font-black uppercase italic leading-none">FILTERS</h2>
                        <button type="button"
                                @click="filtersOpen = false"
                                class="md:hidden flex items-center justify-center w-9 h-9 border-2 border-black bg-white"
                                aria-label="Sluit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </button>
                    </div>

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
                            <input type="range" min="500" max="1500" step="25"
                                   x-model="maxCal"
                                   @change="submit()"
                                   class="w-full h-1.5 accent-black cursor-pointer mb-2.5">
                            <span :class="maxCal > 1000 ? 'bg-red-400' : (maxCal > 700 ? 'bg-orange-400' : 'bg-[var(--lime)]')"
                                  class="inline-block border-2 border-black px-2 py-0.5 text-[9px] font-black uppercase tracking-widest">
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
                <div class="flex-1 min-w-0 w-full">

                    {{-- Page header --}}
                    <div class="flex items-start justify-between mb-1.5 gap-3">
                        <h1 class="text-3xl md:text-5xl font-black uppercase italic leading-[1.05]">
                            POPULAIRE<br>RECEPTEN
                        </h1>
                        <div class="bg-[var(--lime)] border-2 border-black shadow-[3px_3px_0px_0px_#000] px-4 py-2 text-center flex-shrink-0">
                            <div class="text-lg font-black leading-none">{{ $recipes->count() }}</div>
                            <div class="text-[9px] font-black uppercase tracking-widest leading-none mt-0.5">RESULTATEN</div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-5">Ontdek de favorieten van studenten deze week.</p>

                    {{-- Tabs --}}
                    @php $currentSort = $sort ?? request('sort', 'popular'); @endphp
                    <div class="flex gap-5 md:gap-7 border-b-2 border-black mb-6 overflow-x-auto md:overflow-visible"
                         style="scrollbar-width: none;">
                        @foreach(['popular' => 'POPULAIR', 'snel' => 'SNEL', 'gezond' => 'GEZOND'] as $key => $label)
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                               @class([
                                   'text-[11px] font-black uppercase tracking-widest pb-2.5 -mb-px no-underline transition-colors',
                                   'border-b-2 border-brand text-brand' => $currentSort === $key,
                                   'text-gray-400 hover:text-black' => $currentSort !== $key,
                               ])>{{ $label }}</a>
                        @endforeach
                    </div>

                    {{-- Recipe cards --}}
                    @php
                        use Illuminate\Support\Facades\Storage;
                        $badgeCycle  = ['bg-brand text-white', 'bg-purple-600 text-white', 'bg-[var(--lime)] text-black'];
                        $avatarCycle = ['bg-purple-500', 'bg-indigo-400', 'bg-teal-400', 'bg-orange-400', 'bg-pink-500', 'bg-blue-400'];
                    @endphp

                    <div x-data="{ expanded: false }">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($recipes as $i => $recipe)
                            @php
                                $tag         = $recipe->dietTags->first()?->name;
                                $badgeClass  = $badgeCycle[$i % 3];
                                $reviewers   = $recipe->reviews->pluck('user')->filter()->unique('id')->take(3)->values();
                                $extraCount  = max(0, ($recipe->review_count ?? 0) - $reviewers->count());
                            @endphp
                            <div data-recipe-card
                               @click="window.location.href='{{ route('recept', $recipe->id) }}'"
                               @if($i >= 6) x-show="expanded" style="display:none" @endif
                               class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] flex flex-col no-underline text-inherit hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 cursor-pointer">

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
                                    <div class="absolute top-2 right-2 z-10" @click.stop>
                                        <livewire:toggle-favorite :recipe-id="$recipe->id" :compact="true" :key="'fav-'.$recipe->id" />
                                    </div>
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
                                    @if($recipe->review_count > 0 && $reviewers->isNotEmpty())
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex -space-x-2">
                                                    @foreach($reviewers as $reviewer)
                                                        @php
                                                            $color   = $avatarCycle[($reviewer->id ?? 0) % count($avatarCycle)];
                                                            $initial = strtoupper(mb_substr($reviewer->username ?? '?', 0, 1));
                                                        @endphp
                                                        <div class="w-6 h-6 rounded-full {{ $color }} border-2 border-white flex items-center justify-center overflow-hidden">
                                                            @if($reviewer->avatar_url)
                                                                <img src="{{ Storage::url($reviewer->avatar_url) }}" alt="{{ $reviewer->username }}" class="w-full h-full object-cover">
                                                            @else
                                                                <span class="text-[8px] font-black text-white leading-none">{{ $initial }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if($extraCount > 0)
                                                    <span class="text-[8px] font-black bg-[var(--lime)] border border-black px-1 py-0.5 leading-none">+{{ $extraCount }}</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] font-black">★ {{ number_format($recipe->avg_rating, 1) }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 flex flex-col items-center gap-3 border-2 border-dashed border-gray-300">
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
                                class="text-[11px] font-black uppercase tracking-widest px-8 md:px-16 py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_var(--pink)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_var(--pink)] transition-all duration-75">
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
