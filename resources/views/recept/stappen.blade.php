@php
    $steps = [
        ['num' => 1, 'text' => 'Kook de eieren precies 6 minuten in kokend water. Schrik ze daarna direct af onder de koude kraan.'],
        ['num' => 2, 'text' => 'Kook 500ml water met de kruidenmixjes. Voeg de noedels toe en kook voor 2 minuten.'],
        ['num' => 3, 'text' => 'Roer de sambal en sesamolie erdoorheen. Gooi de spinazie erbij op het allerlaatste moment.'],
        ['num' => 4, 'text' => 'Giet in een grote kom, leg het ei erop (doormidden gesneden!) en strooi de lente-ui erover.'],
    ];
@endphp
<div class="grid grid-cols-2 gap-4">
    @foreach($steps as $step)
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] flex overflow-hidden">
            <div class="relative flex-shrink-0 w-28 border-r-2 border-black">
                <img src="{{ asset('images/kater_pasta.png') }}"
                     alt="Stap {{ $step['num'] }}"
                     class="w-full h-full object-cover">
                <span class="absolute top-1 left-1 text-[6px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1 py-0.5 leading-tight">STAP {{ $step['num'] }} VIDEO</span>
                <button class="absolute inset-0 flex items-center justify-center">
                    <span class="w-8 h-8 rounded-full bg-brand flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                        <svg class="w-3 h-3 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </span>
                </button>
                <span class="absolute bottom-1 right-1 text-[7px] font-black bg-black text-white px-1 py-0.5">0:18</span>
            </div>
            <div class="flex-1 p-3">
                <div class="flex items-start justify-between mb-2">
                    <span class="w-7 h-7 rounded-full border-2 border-black flex items-center justify-center text-xs font-black flex-shrink-0">{{ $step['num'] }}</span>
                    <span class="text-[8px] font-black uppercase tracking-widest text-brand cursor-pointer hover:underline">JUMP NAAR</span>
                </div>
                <p class="text-[11px] font-medium leading-snug text-gray-800">{{ $step['text'] }}</p>
            </div>
        </div>
    @endforeach
</div>
