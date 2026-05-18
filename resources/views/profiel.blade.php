<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profiel - Hapklaar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @livewireStyles
    </head>
    <body class="m-0 bg-[var(--pink-soft)] min-h-screen flex flex-col overflow-x-hidden">

        <x-navbar />

        <main class="flex-1 py-6 md:py-10">
            <div class="max-w-5xl mx-auto px-4 md:px-6">

                {{-- ============================================================
                     PROFILE HERO CARD
                ============================================================ --}}
                @php
                    use Illuminate\Support\Facades\Storage;
                    $user = auth()->user();
                    $initial = strtoupper(mb_substr($user->username, 0, 1));
                    $memberYear = $user->created_at?->format('Y') ?: now()->format('Y');
                @endphp

                <div class="relative bg-white border-2 border-black shadow-[6px_6px_0px_0px_var(--hot-pink)] mb-8 overflow-visible">

                    {{-- Top ribbon strip --}}
                    <div class="flex items-center justify-between bg-black text-white px-5 py-2 relative z-10">
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="text-[var(--lime)]">●</span>
                            PROFIEL 
                        </span>
                    </div>

                    {{-- Hero band with subtle dot pattern --}}
                    <div class="relative px-5 md:px-8 pt-10 pb-7"
                         style="background-color: var(--pink-soft); background-image: radial-gradient(circle, rgba(0,0,0,0.07) 1.2px, transparent 1.2px); background-size: 18px 18px;">

                        {{-- Decorative blob accents --}}
                        <div class="absolute top-4 left-6 w-3 h-3 rounded-full bg-[var(--lime)] border-2 border-black"></div>
                        <div class="absolute top-8 right-32 text-2xl select-none opacity-80 hidden md:block" style="transform: rotate(-12deg);">✦</div>

                        <div class="relative flex flex-col md:flex-row items-center md:items-center gap-6 md:gap-7">

                            {{-- Avatar with floating dots --}}
                            <div class="relative flex-shrink-0">
                                {{-- floating accents around avatar --}}
                                <div class="absolute -top-2 -left-3 w-4 h-4 rounded-full bg-[var(--yellow)] border-2 border-black z-10"></div>
                                <div class="absolute top-5 -left-5 w-2.5 h-2.5 rounded-full bg-brand border-2 border-black z-10"></div>
                                <div class="absolute -top-4 right-4 text-xl select-none z-10" style="transform: rotate(15deg);">✨</div>

                                <div class="w-36 h-36 rounded-full bg-gradient-to-br from-brand to-[var(--hot-pink)] border-[3px] border-black flex items-center justify-center shadow-[6px_6px_0px_0px_#000] relative overflow-hidden">
                                    <div class="absolute inset-2 rounded-full border border-white/40"></div>
                                    @if($user->avatar_url)
                                        <img src="{{ Storage::url($user->avatar_url) }}" alt="Avatar" class="w-full h-full object-cover absolute inset-0">
                                    @else
                                        <span class="text-white text-7xl font-black italic leading-none select-none relative" style="text-shadow: 3px 3px 0px rgba(0,0,0,0.25);">{{ $initial }}</span>
                                    @endif
                                </div>
                                {{-- photo upload button --}}
                                <button type="button"
                                        aria-label="Profielfoto uploaden"
                                        onclick="document.getElementById('avatar-input').click()"
                                        class="absolute -bottom-1 -right-1 w-10 h-10 rounded-full bg-[var(--lime)] border-[3px] border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 cursor-pointer">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Identity + bio --}}
                            <div class="flex-1 min-w-0 text-center md:text-left w-full">

                                <h1 class="text-3xl sm:text-4xl md:text-[3.25rem] font-black uppercase italic leading-[0.9] tracking-tight mb-2 truncate">
                                    <span class="relative inline-block">
                                        <span class="absolute inset-x-0 bottom-1 h-3 bg-[var(--lime)] -z-0"></span>
                                        <span class="relative">{{ $user->username }}</span>
                                    </span>
                                </h1>
                                <p class="text-gray-700 text-sm leading-relaxed max-w-xl italic">
                                    "{{ $user->bio ?: 'Nog geen bio. Klik op bewerken en vertel wat jij graag in de pan gooit.' }}"
                                </p>
                            </div>

                            {{-- Action buttons --}}
                            <div class="flex-shrink-0 md:self-start flex flex-row md:flex-col gap-2 w-full md:w-auto justify-center">
                                <button onclick="window.dispatchEvent(new CustomEvent('open-tab', { detail: { tab: 'instellingen' } })); document.getElementById('profiel-tabs').scrollIntoView({ behavior: 'smooth' })"
                                        class="flex items-center gap-1.5 bg-brand text-white text-[10px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    BEWERKEN
                                </button>
                                <button class="flex items-center justify-center gap-1.5 bg-white text-black text-[10px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                    DELEN
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Stats row --}}
                    <div class="grid grid-cols-3 border-t-2 border-black divide-x-2 divide-black">
                        <div class="flex flex-col md:flex-row items-center md:gap-3 gap-2 px-2 md:px-5 py-3 md:py-4 bg-white hover:bg-[var(--pink-soft)] transition-colors text-center md:text-left">
                            <div class="w-9 h-9 bg-[var(--lime)] border-2 border-black flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <div class="leading-tight">
                                <div class="text-xl font-black leading-none">69</div>
                                <div class="text-[8px] font-black uppercase tracking-widest text-gray-500 mt-0.5">RECEPTEN</div>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center md:gap-3 gap-2 px-2 md:px-5 py-3 md:py-4 bg-white hover:bg-[var(--pink-soft)] transition-colors text-center md:text-left">
                            <div class="w-9 h-9 bg-[var(--yellow)] border-2 border-black flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="leading-tight">
                                <div class="text-xl font-black leading-none">{{ $reviews->count() }}</div>
                                <div class="text-[8px] font-black uppercase tracking-widest text-gray-500 mt-0.5">REVIEWS</div>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center md:gap-3 gap-2 px-2 md:px-5 py-3 md:py-4 bg-white hover:bg-[var(--pink-soft)] transition-colors text-center md:text-left">
                            <div class="w-9 h-9 bg-brand border-2 border-black flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                            <div class="leading-tight">
                                <div class="text-xl font-black leading-none">{{ $favorieten->count() }}</div>
                                <div class="text-[8px] font-black uppercase tracking-widest text-gray-500 mt-0.5">FAVORIETEN</div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ============================================================
                     TAB NAV
                ============================================================ --}}
                <div x-data="{ tab: 'favorieten' }" id="profiel-tabs" @open-tab.window="tab = $event.detail.tab">

                <div class="flex gap-2 md:gap-3 mb-8 flex-wrap">
                    <button @click="tab = 'favorieten'"
                            :class="tab === 'favorieten' ? 'bg-brand text-white' : 'bg-white text-black hover:bg-[var(--pink-soft)]'"
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        FAVORIETEN
                    </button>
                    <button @click="tab = 'reviews'"
                            :class="tab === 'reviews' ? 'bg-brand text-white' : 'bg-white text-black hover:bg-[var(--pink-soft)]'"
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        MIJN REVIEWS
                    </button>
                    <button @click="tab = 'instellingen'"
                            :class="tab === 'instellingen' ? 'bg-brand text-white' : 'bg-white text-black hover:bg-[var(--pink-soft)]'"
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        INSTELLINGEN
                    </button>
                </div>

                {{-- ============================================================
                     FAVORIETEN SECTION
                ============================================================ --}}
                <div x-show="tab === 'favorieten'" x-cloak>
                <h2 class="text-sm font-black uppercase tracking-widest mb-5">JE BEST BEWAARDE GEHEIMEN</h2>

                @if($favorieten->isEmpty())
                    <p class="text-sm text-gray-500 italic">Nog geen favorieten. Ga recepten ontdekken!</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @foreach($favorieten as $fav)
                        <a href="{{ route('recept', $fav->id) }}"
                           class="bg-black border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 overflow-hidden cursor-pointer group block">

                            {{-- Image + badges --}}
                            <div class="relative overflow-hidden" style="aspect-ratio:16/9;">
                                @if($fav->image_path)
                                    <img src="{{ asset('storage/' . $fav->image_path) }}"
                                         alt="{{ $fav->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200"></div>
                                @endif

                                {{-- FAVORIET badge top-left --}}
                                <span class="absolute top-3 left-3 bg-[var(--lime)] text-black text-[10px] font-black uppercase tracking-widest px-2.5 py-1.5 border-2 border-black flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    FAVORIET
                                </span>

                                {{-- Badge top-right --}}
                                @if($fav->badge)
                                    <span class="absolute top-3 right-3 bg-brand text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1.5 border-2 border-black">
                                        {{ $fav->badge }}
                                    </span>
                                @endif
                            </div>

                            {{-- Black bottom section: title + info --}}
                            <div class="bg-black px-5 pt-4 pb-4">
                                <h3 class="text-white font-black uppercase italic text-lg leading-tight mb-3">{{ $fav->title }}</h3>

                                <div class="flex items-center gap-5">
                                    @if($fav->prep_time_minutes)
                                    <span class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide text-white">
                                        <svg class="w-4 h-4 text-[var(--lime)]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
                                        </svg>
                                        {{ $fav->prep_time_minutes }} MIN
                                    </span>
                                    @endif
                                    @if($fav->afwas_score)
                                    <span class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide text-white">
                                        <svg class="w-4 h-4 text-[var(--lime)]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/>
                                        </svg>
                                        AFWAS-SCORE: {{ $fav->afwas_score }}/5
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>
                @endif
                </div>{{-- end favorieten --}}

                {{-- ============================================================
                     MIJN REVIEWS SECTION
                ============================================================ --}}
                <div x-show="tab === 'reviews'" x-cloak>
                    <h2 class="text-sm font-black uppercase tracking-widest mb-5">JOUW EERLIJKE MENINGEN</h2>

                    @if($reviews->isEmpty())
                        <p class="text-sm text-gray-500 italic">Je hebt nog geen reviews geschreven.</p>
                    @else
                    <div class="flex flex-col gap-4 mb-8">
                        @foreach($reviews as $review)
                            <a href="{{ route('recept', $review->recipe_id) }}" class="flex flex-col sm:flex-row items-stretch border-2 border-black shadow-[4px_4px_0px_0px_#000] bg-white hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[3px_3px_0px_0px_#000] transition-all duration-75">

                                {{-- Thumbnail --}}
                                <div class="flex-shrink-0 w-20 h-20 my-auto ml-4 mt-4 sm:mt-auto border-2 border-black overflow-hidden">
                                    @if($review->recipe && $review->recipe->image_path)
                                        <img src="{{ asset('storage/' . $review->recipe->image_path) }}"
                                             alt="{{ $review->recipe->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200"></div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 px-5 py-4 min-w-0">
                                    <h3 class="text-sm font-black uppercase leading-snug mb-1">{{ strtoupper($review->recipe->title ?? 'RECEPT VERWIJDERD') }}</h3>

                                    {{-- Stars --}}
                                    <div class="flex gap-0.5 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 text-brand" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>

                                    @if($review->title)
                                        <p class="text-[11px] font-black uppercase mb-1">{{ $review->title }}</p>
                                    @endif
                                    @if($review->body)
                                        <p class="text-xs text-gray-700 leading-relaxed mb-2 whitespace-pre-line">{{ $review->body }}</p>
                                    @endif
                                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ strtoupper($review->created_at->diffForHumans()) }}</p>
                                </div>

                                {{-- Status badge --}}
                                <div class="flex-shrink-0 flex sm:items-end px-4 pb-4 sm:py-4">
                                    <span class="bg-[var(--lime)] text-black text-[8px] font-black uppercase tracking-widest px-3 py-1.5 border border-black whitespace-nowrap">
                                        {{ strtoupper($review->status) }} ✓
                                    </span>
                                </div>

                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>{{-- end reviews --}}

                {{-- ============================================================
                     INSTELLINGEN SECTION
                ============================================================ --}}
                <div x-show="tab === 'instellingen'" x-cloak>

                    {{-- Row 1: Profiel + Account & Beveiliging --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                        {{-- JOUW PROFIEL --}}
                        <div class="border-2 border-black shadow-[4px_4px_0px_0px_#000] bg-white p-6">
                            <livewire:update-profile />
                        </div>

                        {{-- ACCOUNT & BEVEILIGING --}}
                        <div class="border-2 border-black shadow-[4px_4px_0px_0px_#000] bg-white p-6">
                            <h3 class="text-sm font-black uppercase tracking-widest mb-5">ACCOUNT & BEVEILIGING</h3>

                            {{-- Email --}}
                            <div class="mb-5">
                                <label class="block text-[8px] font-black uppercase tracking-widest text-brand mb-1.5">E-MAILADRES</label>
                                <input type="email"
                                       value="{{ auth()->user()->email }}"
                                       readonly
                                       class="w-full border-2 border-black px-3 py-2.5 text-sm font-medium outline-none bg-white text-gray-500 cursor-default">
                            </div>

                            {{-- Wachtwoord --}}
                            <button class="w-full bg-[var(--lime)] text-black text-[10px] font-black uppercase tracking-widest py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                WACHTWOORD WIJZIGEN
                            </button>
                        </div>

                    </div>

                    {{-- Row 2: Notificaties + Gevaar Zone --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

                        {{-- NOTIFICATIES --}}
                        <div class="border-2 border-black shadow-[4px_4px_0px_0px_#000] bg-white p-6">
                            <h3 class="text-sm font-black uppercase tracking-widest mb-5">NOTIFICATIES</h3>

                            @php
                                $notificaties = [
                                    ['label' => 'NIEUWE REACTIES',        'checked' => true],
                                    ['label' => 'IEMAND VOLGT MIJ',       'checked' => true],
                                    ['label' => 'WEKELIJKSE RECEPTENTIPS','checked' => true],
                                    ['label' => 'HAPKLAAR NIEUWSBRIEF',   'checked' => false],
                                ];
                            @endphp

                            <div class="flex flex-col gap-0 divide-y-2 divide-black border-t-2 border-black">
                                @foreach($notificaties as $notif)
                                    <label x-data="{ checked: {{ $notif['checked'] ? 'true' : 'false' }} }"
                                           class="flex items-center justify-between py-3.5 cursor-pointer"
                                           @click.prevent="checked = !checked">
                                        <span class="text-[9px] font-black uppercase tracking-widest">{{ $notif['label'] }}</span>
                                        <div class="w-5 h-5 border-2 flex-shrink-0 flex items-center justify-center transition-colors"
                                             :class="checked ? 'bg-brand border-brand' : 'bg-white border-black'">
                                            <svg x-show="checked" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- GEVAAR ZONE --}}
                        <div class="border-2 border-brand shadow-[4px_4px_0px_0px_var(--hot-pink)] bg-white p-6">
                            <h3 class="text-sm font-black uppercase tracking-widest text-brand mb-4">GEVAAR ZONE</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest leading-relaxed mb-4">
                                ACCOUNT VERWIJDEREN? LET OP: DIT KAN NIET ONGEDAAN WORDEN GEMAAKT.
                            </p>
                            <a href="#"
                               class="text-[10px] font-black uppercase tracking-widest underline underline-offset-2 text-black hover:text-brand transition-colors">
                                ACCOUNT PERMANENT VERWIJDEREN
                            </a>
                        </div>

                    </div>

                </div>{{-- end instellingen --}}

                </div>{{-- end x-data --}}

            </div>
        </main>

        <x-footer />

        @livewireScripts
    </body>
</html>
