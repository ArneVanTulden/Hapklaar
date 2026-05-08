{{-- Rating summary --}}
<div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5 flex gap-8 items-center">
    <div class="flex-shrink-0 text-center">
        <p class="text-6xl font-black leading-none">4.8</p>
        <div class="flex gap-0.5 justify-center my-1">
            @for($i = 0; $i < 5; $i++)
                <svg class="w-4 h-4 text-brand" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">GEBASEERD OP 247 REVIEWS</p>
    </div>
    <div class="flex-1 space-y-1.5">
        @php
            $bars = [
                ['label' => '5 STER', 'count' => 198, 'pct' => 80],
                ['label' => '4 STER', 'count' => 34,  'pct' => 14],
                ['label' => '3 STER', 'count' => 11,  'pct' => 4],
                ['label' => '2 STER', 'count' => 4,   'pct' => 2],
                ['label' => '1 STER', 'count' => 0,   'pct' => 0],
            ];
        @endphp
        @foreach($bars as $b)
            <div class="flex items-center gap-2">
                <span class="text-[8px] font-black uppercase tracking-widest w-10 flex-shrink-0">{{ $b['label'] }}</span>
                <div class="flex-1 h-2.5 bg-gray-100 border border-black overflow-hidden">
                    <div class="h-full bg-[var(--lime)]" style="width: {{ $b['pct'] }}%"></div>
                </div>
                <span class="text-[8px] font-black w-6 text-right">{{ $b['count'] }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- Action bar --}}
<div class="flex items-center justify-between">
    <button class="flex items-center gap-2 bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        SCHRIJF EEN REVIEW
    </button>
    <div class="flex gap-1">
        @foreach(['MEEST RECENT', 'HOOGSTE', 'LAAGSTE', 'MET FOTO'] as $sort)
            <button class="text-[8px] font-black uppercase tracking-widest px-3 py-2 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $sort === 'MEEST RECENT' ? 'bg-[var(--lime)]' : 'bg-white hover:bg-[var(--pink-soft)]' }} transition-colors">{{ $sort }}</button>
        @endforeach
    </div>
</div>

{{-- Reviews --}}
@php
    $reviews = [
        [
            'initial' => 'T',
            'name'    => 'THOMAS V.D. BILT',
            'badge'   => 'GEVERIFIEERDE STUDENT',
            'when'    => '3 UUR GELEDEN',
            'stars'   => 5,
            'title'   => 'HEEFT LETTERLIJK MIJN LEVEN GERED',
            'body'    => 'Na het lustrumdfeest gisteravond was ik ervan overtuigd dat ik nooit meer zou kunnen lopen. Deze ramen met die extra pindakaas hack? Absolute game changer. Heb ook wat sriracha toegevoegd voor de extra kick. 10/10 zou het weer maken na elke escalatie.',
            'photos'  => true,
            'helpful' => 42,
            'color'   => 'bg-brand text-white',
        ],
        [
            'initial' => 'L',
            'name'    => 'LAURA STEVENS',
            'badge'   => 'GEVERIFIEERDE STUDENT',
            'when'    => 'GISTEREN',
            'stars'   => 4,
            'title'   => 'SNEL EN GOEDKOOP',
            'body'    => 'Perfect voor als je geen zin hebt om naar de Apple te lopen en alleen nog maar wat eieren en ramen in de kast hebt liggen. De pindakaas klinkt raar maar maakt de saus super creamy. Iets te zout als je het hele kruidenzakje gebruikt, dus gebruik de helft!',
            'photos'  => false,
            'helpful' => 18,
            'color'   => 'bg-[var(--lime)] text-black',
        ],
        [
            'initial' => 'M',
            'name'    => 'MAARTEN DE J.',
            'badge'   => 'GEVERIFIEERDE STUDENT',
            'when'    => '3 DAGEN GELEDEN',
            'stars'   => 5,
            'title'   => 'ELKE CENT WAARD',
            'body'    => 'Ik heb dit nu al 3 keer gegeten deze week. Voor minder dan 2 euro per portie kun je niet klagen. De tip voor de voice control is trouwens goud als je met vette ramen-vingers je scherm niet wilt aanraken.',
            'photos'  => false,
            'helpful' => 29,
            'color'   => 'bg-violet-200 text-black',
        ],
    ];
@endphp

@foreach($reviews as $review)
    <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-full {{ $review['color'] }} border-2 border-black flex items-center justify-center text-sm font-black flex-shrink-0">{{ $review['initial'] }}</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-black uppercase">{{ $review['name'] }}</span>
                        <span class="text-[7px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1.5 py-0.5">{{ $review['badge'] }}</span>
                    </div>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $review['when'] }}</p>
                </div>
            </div>
            <div class="flex gap-0.5">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i < $review['stars'] ? 'text-brand' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
        </div>
        <p class="text-[11px] font-black uppercase mb-1">{{ $review['title'] }}</p>
        <p class="text-[11px] text-gray-700 leading-relaxed mb-3">{{ $review['body'] }}</p>
        @if($review['photos'])
            <div class="flex gap-2 mb-3">
                @for($p = 0; $p < 2; $p++)
                    <div class="w-20 h-16 border-2 border-black overflow-hidden">
                        <img src="{{ asset('images/kater_pasta.png') }}" class="w-full h-full object-cover" alt="review foto">
                    </div>
                @endfor
            </div>
        @endif
        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
            <div class="flex items-center gap-2">
                <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">WAS DIT HELPFUL?</span>
                <button class="flex items-center gap-1 bg-[var(--lime)] border-2 border-black px-2 py-1 shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                    <span class="text-[9px] font-black">{{ $review['helpful'] }}</span>
                </button>
            </div>
            <button class="text-[9px] font-black uppercase tracking-widest text-brand hover:underline">ANTWOORD</button>
        </div>
    </div>
@endforeach

{{-- Load more --}}
<div class="flex justify-center pt-2">
    <button class="text-[10px] font-black uppercase tracking-widest px-12 py-4 border-2 border-black bg-white shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
        LAAD MEER REVIEWS
    </button>
</div>
