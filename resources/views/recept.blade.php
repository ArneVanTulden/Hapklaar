<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ultieme Kater Ramen - Hapklaar</title>
        <x-pwa-head />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <script type="module" src="https://cdn.jsdelivr.net/npm/@mux/mux-player@3"></script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    init() {
                        const p = document.getElementById('recipe-player');
                        if (p) p.addEventListener('timeupdate', () => { this.currentTime = p.currentTime; });
                    },
                    jumpTo(t) {
                        const p = document.getElementById('recipe-player');
                        if (!p) return;
                        const parts = String(t).split(':');
                        p.currentTime = parts.length === 2 ? +parts[0] * 60 + +parts[1] : +parts[0];
                        p.play();
                        p.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    },

                    // Voice control
                    voiceRecording: false,
                    voiceProcessing: false,
                    voiceStatus: '',
                    _mediaRecorder: null,
                    _audioChunks: [],
                    _stream: null,

                    async toggleVoice() {
                        if (this.voiceRecording) {
                            this._mediaRecorder?.stop();
                            return;
                        }
                        this.voiceStatus = '';
                        try {
                            this._stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                            this._audioChunks = [];
                            this._mediaRecorder = new MediaRecorder(this._stream);
                            this._mediaRecorder.ondataavailable = e => { if (e.data.size > 0) this._audioChunks.push(e.data); };
                            this._mediaRecorder.onstop = () => this._sendVoice();
                            this._mediaRecorder.start();
                            this.voiceRecording = true;
                            this.voiceStatus = 'Luisteren...';
                            setTimeout(() => { if (this.voiceRecording) this._mediaRecorder?.stop(); }, 6000);
                        } catch {
                            this.voiceStatus = 'Microfoon toegang geweigerd';
                            setTimeout(() => { this.voiceStatus = ''; }, 3000);
                        }
                    },

                    async _sendVoice() {
                        this.voiceRecording = false;
                        this.voiceProcessing = true;
                        this.voiceStatus = 'Verwerken...';
                        this._stream?.getTracks().forEach(t => t.stop());

                        const mime = this._audioChunks[0]?.type || 'audio/webm';
                        const ext  = mime.includes('mp4') ? 'mp4' : mime.includes('ogg') ? 'ogg' : 'webm';
                        const blob = new Blob(this._audioChunks, { type: mime });
                        const form = new FormData();
                        form.append('audio', blob, `voice.${ext}`);
                        form.append('_token', '{{ csrf_token() }}');

                        try {
                            const res  = await fetch('{{ route('recept.voice', $recipe->id) }}', { method: 'POST', body: form });
                            const data = await res.json();
                            if (data.timestamp) {
                                this.jumpTo(data.timestamp);
                                this.voiceStatus = `Stap ${data.step} gevonden`;
                            } else {
                                this.voiceStatus = data.transcript ? `"${data.transcript}" — niet gevonden` : 'Niet gevonden';
                            }
                        } catch {
                            this.voiceStatus = 'Fout opgetreden';
                        }

                        this.voiceProcessing = false;
                        setTimeout(() => { this.voiceStatus = ''; }, 4000);
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
                            <div class="absolute -top-5 -right-5 z-10">
                                <livewire:toggle-favorite :recipe-id="$recipe->id" />
                            </div>
                            <div class="border-2 border-black overflow-hidden" style="aspect-ratio: 16/10;">
                                @if($recipe->video_url)
                                    <mux-player
                                        id="recipe-player"
                                        src="{{ $recipe->video_url }}"
                                        poster="{{ asset('storage/' . $recipe->image_path) }}"
                                        env-key="{{ config('services.mux.data_env_key') }}"
                                        style="width: 100%; height: 100%; --media-object-fit: cover;"
                                        playsinline
                                    ></mux-player>
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
                                        :disabled="voiceProcessing"
                                        :class="voiceRecording ? 'bg-red-500 animate-pulse border-red-700' : 'bg-brand'"
                                        class="flex items-center gap-2 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 disabled:opacity-60 disabled:cursor-not-allowed disabled:translate-x-0 disabled:translate-y-0 disabled:shadow-[3px_3px_0px_0px_#000]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 016 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                    <span x-text="voiceRecording ? 'OPNEMEN... (STOP)' : voiceProcessing ? 'VERWERKEN...' : 'VOICE CONTROL'"></span>
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
