<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Ultieme Kater Ramen - Hapklaar</title>
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <script type="module" src="https://cdn.jsdelivr.net/npm/@mux/mux-player@3"></script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @if($recipe->video_url)
                @vite('resources/js/voice.js')
            @endif
        @endif
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col overflow-x-hidden">

        <x-navbar />

        <script>
            function receptPageData() {
                return {
                    tab: 'stappen',
                    portions: 1,
                    currentTime: 0,
                    steps: @json($recipe->steps->map(fn($s) => ['n' => $s->step_number, 'ts' => $s->video_timestamp])),
                    get activeStep() {
                        const toSec = t => { if (!t) return null; const p = String(t).split(':'); return p.length === 2 ? +p[0]*60 + +p[1] : +p[0]; };
                        const timed = this.steps.filter(s => s.ts).map(s => ({ n: s.n, sec: toSec(s.ts) })).sort((a,b) => a.sec - b.sec);
                        let active = null;
                        for (const s of timed) { if (this.currentTime >= s.sec) active = s.n; }
                        return active;
                    },
                    isFullscreen: false,
                    videoEnded: false,
                    controlsVisible: true,
                    endVideoSec: (() => { const t = @json($recipe->end_video_timestamp); if (!t) return null; const p = String(t).split(':'); return p.length === 2 ? +p[0]*60 + +p[1] : +p[0]; })(),
                    get showEndButton() { return this.endVideoSec !== null && this.currentTime >= this.endVideoSec && !this.videoEnded; },

                    init() {
                        const p = this.$refs.player;
                        if (p) {
                            p.addEventListener('timeupdate', () => { this.currentTime = p.currentTime; });
                            p.addEventListener('ended', () => { this.videoEnded = true; });
                            p.addEventListener('play', () => { this.videoEnded = false; });

                            let hideTimer;
                            const showCtrl = () => { this.controlsVisible = true; clearTimeout(hideTimer); };
                            const hideCtrl = (delay = 3000) => { clearTimeout(hideTimer); hideTimer = setTimeout(() => { this.controlsVisible = false; }, delay); };
                            const wrapper = this.$refs.videoWrapper;
                            p.addEventListener('pause', showCtrl);
                            p.addEventListener('play', () => hideCtrl(3000));
                            wrapper.addEventListener('mousemove', () => { showCtrl(); if (!p.paused) hideCtrl(2500); });
                            wrapper.addEventListener('mouseleave', () => { if (!p.paused) hideCtrl(500); });
                            p.addEventListener('touchstart', () => { showCtrl(); if (!p.paused) hideCtrl(3000); }, { passive: true });
                        }

                        const onFsChange = () => {
                            const fs = document.fullscreenElement || document.webkitFullscreenElement;
                            this.isFullscreen = !!fs;
                            const wrapper = this.$refs.videoWrapper;
                            [this.$refs.voiceOverlay, this.$refs.endedOverlay, this.$refs.endedButtons, this.$refs.endButton].forEach(el => {
                                if (!el) return;
                                if (fs && !fs.contains(el)) {
                                    fs.appendChild(el);
                                } else if (!fs && wrapper && !wrapper.contains(el)) {
                                    wrapper.appendChild(el);
                                }
                            });
                        };
                        document.addEventListener('fullscreenchange', onFsChange);
                        document.addEventListener('webkitfullscreenchange', onFsChange);
                    },
                    toggleFullscreen() {
                        if (document.fullscreenElement || document.webkitFullscreenElement) {
                            (document.exitFullscreen || document.webkitExitFullscreen).call(document);
                        } else {
                            const w = this.$refs.videoWrapper;
                            (w.requestFullscreen || w.webkitRequestFullscreen).call(w);
                        }
                    },

                    jumpTo(t) {
                        const p = this.$refs.player;
                        if (!p || t == null) return;
                        const sec = String(t).includes(':')
                            ? t.split(':').reduce((acc, v, i, a) => acc + +v * Math.pow(60, a.length - 1 - i), 0)
                            : +t;
                        if (isNaN(sec)) return;
                        p.currentTime = sec;
                        p.play();
                        p.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    },

                    // Voice control
                    voiceActive: false,
                    voiceStatus: '',
                    lastKnownStep: null,

                    async toggleVoice() {
                        if (!window.VAD) { this.voiceStatus = 'Voice laden...'; return; }

                        await window.VAD.toggle({
                            recipeId:  {{ $recipe->id }},
                            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                            getCurrentStep: () => this.activeStep ?? this.lastKnownStep ?? 0,
                            onMatch:   (timestamp, step) => { if (step) this.lastKnownStep = step; this.jumpTo(timestamp); },
                            onAction:  (action) => { const p = this.$refs.player; if (!p) return; action === 'pause' ? p.pause() : p.play(); },
                            onStatus:  (msg) => { this.voiceStatus = msg },
                            onToggle:  (active) => { this.voiceActive = active },
                        });
                    },
                };
            }
        </script>

        <main class="flex-1 py-6 md:py-8" x-data="receptPageData()">
            <div class="max-w-6xl mx-auto px-4 md:px-6">

                {{-- ============================================================
                     RECIPE HEADER
                ============================================================ --}}
                <div class="mb-5">
                    <h1 class="text-2xl md:text-4xl font-black uppercase italic leading-tight mb-1">{{ $recipe->title }}</h1>
                    <p class="text-brand font-bold italic text-sm mb-3">{{ $recipe->description }}</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000]">{{ $recipe->prep_time_minutes }} MIN</span>
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000]">{{ (int) $recipe->calories_per_portion }} KCAL</span>
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 border-2 border-black bg-violet-200 shadow-[2px_2px_0px_0px_#000]">AFWAS-SCORE {{ $recipe->afwas_score }}</span>
                    </div>
                </div>

                {{-- ============================================================
                     TWO-COLUMN LAYOUT (single col on mobile)
                ============================================================ --}}
                <div class="grid gap-7 grid-cols-1 lg:grid-cols-[minmax(0,1fr)_18rem] items-start">

                    {{-- A: image + action buttons (col 1, row 1) --}}
                    <div class="min-w-0 lg:col-start-1 lg:row-start-1">

                        {{-- Recipe image / video --}}
                        <div class="relative mb-4">
                            <div class="absolute -top-5 -right-5 z-20">
                                <livewire:toggle-favorite :recipe-id="$recipe->id" />
                            </div>
                            <div x-ref="videoWrapper"
                             class="border-2 border-black overflow-hidden relative"
                             :style="isFullscreen ? 'width:100%;height:100%;' : 'aspect-ratio:16/10;'">
                                @if($recipe->video_url)
                                    <mux-player
                                        id="recipe-player"
                                        x-ref="player"
                                        src="{{ $recipe->video_url }}"
                                        poster="{{ asset('storage/' . $recipe->image_path) }}"
                                        env-key="{{ config('services.mux.data_env_key') }}"
                                        style="width: 100%; height: 100%; --media-object-fit: cover;"
                                        playsinline
                                    ></mux-player>

                                    {{-- Video end overlay --}}
                                    <div x-ref="endedOverlay"
                                         x-show="videoEnded"
                                         x-transition:enter="transition-opacity duration-500"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         class="absolute inset-0 z-10 bg-gray-800/80 pointer-events-none">
                                    </div>

                                    {{-- Video end action buttons --}}
                                    <div x-ref="endedButtons"
                                         x-show="videoEnded"
                                         x-transition:enter="transition-opacity duration-500"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-3 pointer-events-none">
                                        @auth
                                        <form method="POST" action="{{ route('recept.gemaakt', $recipe->id) }}" class="pointer-events-auto">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2 bg-brand text-white text-[11px] font-black uppercase tracking-widest px-6 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                IK HEB DIT GEMAAKT
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="pointer-events-auto flex items-center gap-2 bg-brand text-white text-[11px] font-black uppercase tracking-widest px-6 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            IK HEB DIT GEMAAKT
                                        </a>
                                        @endauth
                                        <button class="pointer-events-auto flex items-center gap-2 bg-white text-black text-[11px] font-black uppercase tracking-widest px-6 py-3.5 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            VERWIJDER UIT INVENTARIS
                                        </button>
                                    </div>

                                    {{-- End button overlay --}}
                                    <div x-ref="endButton"
                                         x-show="showEndButton"
                                         x-transition:enter="transition-opacity duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         :class="controlsVisible ? 'bottom-14' : 'bottom-3'"
                                         class="absolute right-3 z-20 pointer-events-auto transition-all duration-200">
                                        <button @click="$refs.player.currentTime = $refs.player.duration"
                                                class="flex items-center gap-2 bg-brand text-white text-[10px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            KLAAR
                                        </button>
                                    </div>

                                    {{-- Voice overlay: injected into fullscreen element via JS --}}
                                    <div x-ref="voiceOverlay" x-show="isFullscreen" x-cloak
                                         style="position:fixed;top:16px;left:16px;z-index:2147483647;"
                                         class="flex flex-col items-start gap-1 pointer-events-none">
                                        <div class="pointer-events-auto flex flex-col items-start gap-1">
                                            <button @click="toggleVoice()"
                                                    :class="voiceActive ? 'bg-green-500 border-green-700' : 'bg-brand'"
                                                    class="flex items-center gap-2 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                                <svg class="w-4 h-4" :class="voiceActive ? 'animate-pulse' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 016 0v6a3 3 0 01-3 3z"/>
                                                </svg>
                                                <span x-text="voiceActive ? 'VOICE AAN' : 'VOICE CONTROL'"></span>
                                            </button>
                                            <span x-show="voiceStatus" x-text="voiceStatus" class="text-[10px] font-bold text-white bg-black/60 px-2 py-0.5"></span>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $recipe->image_path) }}"
                                         alt="{{ $recipe->title }}"
                                         class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($recipe->video_url)
                            <div class="flex flex-col gap-1">
                                <button @click="toggleVoice()"
                                        :class="voiceActive ? 'bg-green-500 border-green-700' : 'bg-brand'"
                                        class="flex items-center gap-2 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    <svg class="w-4 h-4" :class="voiceActive ? 'animate-pulse' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 016 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                    <span x-text="voiceActive ? 'VOICE AAN' : 'VOICE CONTROL'"></span>
                                </button>
                                <span x-show="voiceStatus" x-text="voiceStatus" class="text-[10px] font-bold text-brand px-1"></span>
                            </div>
                            @endif
                            <button class="flex items-center gap-2 bg-white text-black text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                SHARE
                            </button>
                        </div>

                    </div>{{-- /A --}}

                    {{-- B: Ingredients (col 2 desktop spans both rows, between A and C on mobile) --}}
                    <aside class="lg:col-start-2 lg:row-start-1 lg:row-span-2">
                        <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] p-5">

                            {{-- Header + portions counter --}}
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base font-black uppercase">INGREDIËNTEN</h2>
                                <div class="flex items-center border-2 border-black">
                                    <button @click="portions = Math.max(1, portions - 1)"
                                            class="w-8 h-8 flex items-center justify-center font-black text-base border-r-2 border-black hover:bg-[var(--pink-soft)] transition-colors">−</button>
                                    <span class="w-8 h-8 flex items-center justify-center font-black text-sm bg-[var(--lime)]" x-text="portions">1</span>
                                    <button @click="portions++"
                                            class="w-8 h-8 flex items-center justify-center font-black text-base border-l-2 border-black hover:bg-[var(--pink-soft)] transition-colors">+</button>
                                </div>
                            </div>

                            {{-- Ingredients list --}}
                            <ul class="divide-y divide-gray-100">
                                @foreach($recipe->ingredients as $ingredient)
                                    <li class="flex items-center gap-3 py-2.5">
                                        <svg class="w-3 h-3 flex-shrink-0 text-brand" fill="currentColor" viewBox="0 0 12 12">
                                            <path d="M6 0L7.5 4.5L12 6L7.5 7.5L6 12L4.5 7.5L0 6L4.5 4.5Z"/>
                                        </svg>
                                        <span class="text-[12px] font-medium leading-snug">
                                            @if($ingredient->pivot->quantity)
                                                <span x-text="({{ $ingredient->pivot->quantity }} * portions) % 1 === 0 ? ({{ $ingredient->pivot->quantity }} * portions) : ({{ $ingredient->pivot->quantity }} * portions).toFixed(1)"></span>
                                            @endif
                                            @if($ingredient->pivot->unit && $ingredient->pivot->unit !== 'stuks') {{ $ingredient->pivot->unit }} @endif
                                            {{ $ingredient->canonical_name }}
                                            @if($ingredient->pivot->notes) ({{ $ingredient->pivot->notes }})@endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Shopping list button --}}
                            <div class="border-t-2 border-black mt-4 pt-4">
                                @auth
                                    <form method="POST" action="{{ route('recept.boodschappen', $recipe->id) }}">
                                        @csrf
                                        <input type="hidden" name="portions" :value="portions">
                                        <button type="submit" class="w-full text-[11px] font-black uppercase tracking-widest px-4 py-4 border-2 border-black bg-brand text-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                                            VOEG TOE AAN BOODSCHAPPENLIJST
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="block w-full text-center text-[11px] font-black uppercase tracking-widest px-4 py-4 border-2 border-black bg-brand text-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">
                                        VOEG TOE AAN BOODSCHAPPENLIJST
                                    </a>
                                @endauth
                                @if(session('boodschappen_success'))
                                    <p class="mt-2 text-[10px] font-black text-green-700 uppercase tracking-widest">{{ session('boodschappen_success') }}</p>
                                @endif
                            </div>

                        </div>
                    </aside>

                    {{-- C: tabs + content (col 1 desktop, row 2) --}}
                    <div class="min-w-0 lg:col-start-1 lg:row-start-2">

                        {{-- Tabs --}}
                        <div class="flex gap-4 md:gap-6 border-b-2 border-black mb-6 items-end">
                            <button @click="tab = 'stappen'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'stappen'" class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">STAPPEN</span>
                                <span x-show="tab !== 'stappen'" x-cloak class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">STAPPEN</span>
                            </button>
                            <button @click="tab = 'voedingswaarden'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'voedingswaarden'" x-cloak class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">VOEDINGSWAARDEN</span>
                                <span x-show="tab !== 'voedingswaarden'" class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">VOEDINGSWAARDEN</span>
                            </button>
                            <button @click="tab = 'reviews'" class="text-[11px] font-black uppercase tracking-widest">
                                <span x-show="tab === 'reviews'" x-cloak class="block bg-[var(--lime)] border-2 border-black px-4 py-2.5 mb-[-2px]">REVIEWS</span>
                                <span x-show="tab !== 'reviews'" class="block text-gray-400 hover:text-black italic pb-2.5 transition-colors">REVIEWS</span>
                            </button>
                        </div>

                        {{-- STAPPEN --}}
                        <div x-show="tab === 'stappen'">
                            @include('recept.stappen')
                        </div>

                        {{-- VOEDINGSWAARDEN --}}
                        <div x-show="tab === 'voedingswaarden'" x-cloak class="space-y-6">
                            @include('recept.voedingswaarden')
                        </div>

                        {{-- REVIEWS --}}
                        <div x-show="tab === 'reviews'" x-cloak class="space-y-5">
                            @include('recept.reviews')
                        </div>

                    </div>{{-- /C --}}

                </div>

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
