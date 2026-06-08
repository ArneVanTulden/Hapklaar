<div class="space-y-5">

    {{-- Rating summary --}}
    <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5 flex flex-col sm:flex-row gap-5 sm:gap-8 sm:items-center">
        <div class="flex-shrink-0 text-center">
            <p class="text-6xl font-black leading-none">{{ number_format($avg, 1) }}</p>
            <div class="flex gap-0.5 justify-center my-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($avg) ? 'text-brand' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">GEBASEERD OP {{ $total }} REVIEW{{ $total === 1 ? '' : 'S' }}</p>
        </div>
        <div class="flex-1 space-y-1.5">
            @foreach([5, 4, 3, 2, 1] as $star)
                @php
                    $count = $buckets[$star] ?? 0;
                    $pct   = $total ? round(($count / $total) * 100) : 0;
                @endphp
                <div class="flex items-center gap-2">
                    <span class="text-[8px] font-black uppercase tracking-widest w-10 flex-shrink-0">{{ $star }} STER</span>
                    <div class="flex-1 h-2.5 bg-gray-100 border border-black overflow-hidden">
                        <div class="h-full bg-[var(--lime)]" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-[8px] font-black w-6 text-right">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Action bar --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        @auth
            @if(! $hasOwn)
                <button wire:click="toggleForm" class="flex items-center gap-2 bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    {{ $showForm ? 'ANNULEER' : 'SCHRIJF EEN REVIEW' }}
                </button>
            @else
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">JE HEBT AL EEN REVIEW GEPLAATST</span>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-2 bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                LOG IN OM EEN REVIEW TE SCHRIJVEN
            </a>
        @endauth

        <div class="flex gap-1 flex-wrap">
            @foreach(['recent' => 'MEEST RECENT', 'highest' => 'HOOGSTE', 'lowest' => 'LAAGSTE'] as $key => $label)
                <button wire:click="setSort('{{ $key }}')" class="text-[8px] font-black uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $sort === $key ? 'bg-[var(--lime)]' : 'bg-white hover:bg-[var(--pink-soft)]' }} transition-colors">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    @if(session('review_success'))
        <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">{{ session('review_success') }}</p>
    @endif

    {{-- Review form --}}
    @auth
        @if($showForm && ! $hasOwn)
            <form wire:submit.prevent="submit" class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5 space-y-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest mb-2">JOUW BEOORDELING</label>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="setRating({{ $i }})" class="p-1">
                                <svg class="w-7 h-7 {{ $i <= $rating ? 'text-brand' : 'text-gray-200' }} hover:text-brand transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest mb-2">TITEL</label>
                    <input type="text" wire:model="title" maxlength="255" class="w-full text-[12px] font-medium px-3 py-2.5 border-2 border-black bg-white focus:outline-none focus:bg-[var(--lime)]/30">
                    @error('title') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest mb-2">JOUW REVIEW</label>
                    <textarea wire:model="body" rows="4" maxlength="5000" class="w-full text-[12px] font-medium px-3 py-2.5 border-2 border-black bg-white focus:outline-none focus:bg-[var(--lime)]/30 resize-none"></textarea>
                    @error('body') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-brand text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        PLAATS REVIEW
                    </button>
                    <button type="button" wire:click="toggleForm" class="bg-white text-black text-[10px] font-black uppercase tracking-widest px-5 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                        ANNULEER
                    </button>
                </div>
            </form>
        @endif
    @endauth

    {{-- Reviews --}}
    @forelse($reviews as $review)
        @php
            $username = $review->user->username ?? 'GEBRUIKER';
            $initial  = strtoupper(mb_substr($username, 0, 1));
            $palette  = ['bg-brand text-white', 'bg-[var(--lime)] text-black', 'bg-violet-200 text-black'];
            $color    = $palette[$review->id % count($palette)];
            $canDelete = Auth::check() && (Auth::id() === $review->user_id || (Auth::user()->role ?? 'user') === 'admin');
        @endphp
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5" wire:key="review-{{ $review->id }}">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    @if(!empty($review->user->avatar_url))
                        <img src="{{ asset($review->user->avatar_url) }}" alt="{{ $username }}" class="w-9 h-9 rounded-full border-2 border-black object-cover flex-shrink-0">
                    @else
                        <span class="w-9 h-9 rounded-full {{ $color }} border-2 border-black flex items-center justify-center text-sm font-black flex-shrink-0">{{ $initial }}</span>
                    @endif
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black uppercase">{{ strtoupper($username) }}</span>
                            <span class="text-[7px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1.5 py-0.5">GEVERIFIEERDE STUDENT</span>
                        </div>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ strtoupper($review->created_at->diffForHumans()) }}</p>
                    </div>
                </div>
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-brand' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            @if($review->title)
                <p class="text-[11px] font-black uppercase mb-1">{{ $review->title }}</p>
            @endif
            <p class="text-[11px] text-gray-700 leading-relaxed mb-3 whitespace-pre-line">{{ $review->body }}</p>
            @if($review->photos->isNotEmpty())
                <div class="flex gap-2 mb-3">
                    @foreach($review->photos as $photo)
                        <div class="w-20 h-16 border-2 border-black overflow-hidden">
                            <img src="{{ asset($photo->photo_url) }}" class="w-full h-full object-cover" alt="review foto">
                        </div>
                    @endforeach
                </div>
            @endif
            @if($canDelete)
                <div class="flex items-center justify-end border-t border-gray-100 pt-3">
                    <button wire:click="deleteReview({{ $review->id }})" class="text-[9px] font-black uppercase tracking-widest text-red-600 hover:underline">VERWIJDER</button>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-8 text-center">
            <p class="text-[11px] font-black uppercase tracking-widest text-gray-500">NOG GEEN REVIEWS. WEES DE EERSTE!</p>
        </div>
    @endforelse
</div>
