<section class="py-16 border-b-2 border-black bg-white">
    <div class="max-w-6xl mx-auto px-6">

        <div class="flex items-center gap-4 mb-10">
            <h2 class="text-4xl font-black uppercase">TRENDING RECEPTEN</h2>
            <span class="bg-[#C8FF00] text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 border-2 border-black">HOT &amp; FRESH</span>
        </div>

        @if ($recipes->isEmpty())
            <p class="text-gray-400 font-bold uppercase text-sm tracking-wider">Nog geen recepten beschikbaar.</p>
        @else
            <div class="grid grid-cols-3 gap-6">
                @foreach ($recipes as $index => $recipe)
                    @php
                        $badges = [
                            ['label' => 'STUDENT FAVORIET', 'class' => 'bg-brand text-white'],
                            ['label' => 'HOT DEAL',         'class' => 'bg-[#FF6B00] text-white'],
                            ['label' => 'LAAGSTE AFWAS',    'class' => 'bg-[#C8FF00] text-black'],
                        ];
                        $badge = $badges[$index] ?? null;
                    @endphp

                    <div class="border-2 border-black shadow-[6px_6px_0px_0px_#000] flex flex-col bg-white">

                        {{-- Image --}}
                        <div class="relative border-b-2 border-black overflow-hidden" style="aspect-ratio:4/3;">
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300 text-4xl">
                                🍽
                            </div>
                            @if ($badge)
                                <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2 py-1 {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-black uppercase text-lg leading-tight mb-3">{{ $recipe->title }}</h3>

                            <div class="flex items-center gap-3 mb-4 text-[10px] font-bold uppercase text-gray-500 tracking-wide flex-wrap">
                                @if ($recipe->prep_time_minutes)
                                    <span>⏱ {{ $recipe->prep_time_minutes }} MIN</span>
                                @endif
                                @if ($recipe->calories_per_portion)
                                    <span>⚡ {{ (int) $recipe->calories_per_portion }} KCAL</span>
                                @endif
                                @if ($recipe->afwas_score)
                                    <span>🍽 {{ str_repeat('|', min($recipe->afwas_score, 5)) }}</span>
                                @endif
                            </div>

                            <a href="#"
                               class="mt-auto text-center text-[11px] font-black uppercase tracking-widest bg-brand text-white no-underline px-4 py-3 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
                                LAAT ME ZIEN!
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
