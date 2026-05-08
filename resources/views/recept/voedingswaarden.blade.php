{{-- Calorieën --}}
<div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-6">
    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">CALORIEËN PER PORTIE</p>
    <p class="text-5xl font-black italic text-brand mb-3">650 KCAL</p>
    <div class="h-3 bg-gray-100 border border-black overflow-hidden mb-1">
        <div class="h-full bg-[var(--lime)]" style="width: 32%"></div>
    </div>
    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">32% VAN JE DAGELIJKSE INNAME</p>
</div>

{{-- Macronutriënten --}}
<div>
    <p class="text-[10px] font-black uppercase tracking-widest mb-3">MACRONUTRIËNTEN</p>
    <div class="grid grid-cols-3 gap-3">
        @php
            $macros = [
                ['label' => 'EIWITTEN',     'value' => '28G', 'sub' => null,                   'pct' => 34, 'color' => 'bg-brand'],
                ['label' => 'KOOLHYDRATEN', 'value' => '84G', 'sub' => 'WAARVAN SUIKERS: 12G', 'pct' => 65, 'color' => 'bg-[var(--lime)]'],
                ['label' => 'VETTEN',       'value' => '22G', 'sub' => 'WAARVAN VERZADIGD: 7G','pct' => 27, 'color' => 'bg-violet-400'],
            ];
        @endphp
        @foreach($macros as $m)
            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $m['label'] }}</p>
                <p class="text-2xl font-black mb-2">{{ $m['value'] }}</p>
                <div class="h-2 bg-gray-100 border border-black overflow-hidden mb-2">
                    <div class="h-full {{ $m['color'] }}" style="width: {{ $m['pct'] }}%"></div>
                </div>
                @if($m['sub'])
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">{{ $m['sub'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- Vitaminen & Mineralen --}}
<div>
    <p class="text-[10px] font-black uppercase tracking-widest mb-3">VITAMINEN & MINERALEN</p>
    @php
        $vitamins = [
            ['label' => 'VITAMINE A', 'value' => '450μg', 'pct' => 56, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'VITAMINE C', 'value' => '12mg',  'pct' => 13, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'VITAMINE D', 'value' => '2.5μg', 'pct' => 25, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'IJZER',      'value' => '4.2mg', 'pct' => 30, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'CALCIUM',    'value' => '180mg', 'pct' => 18, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'KALIUM',     'value' => '850mg', 'pct' => 43, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'NATRIUM',    'value' => '1.8g',  'pct' => 78, 'color' => 'bg-brand'],
            ['label' => 'VEZELS',     'value' => '5g',    'pct' => 20, 'color' => 'bg-[var(--lime)]'],
        ];
    @endphp
    <div class="grid grid-cols-4 gap-3">
        @foreach($vitamins as $v)
            <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $v['label'] }}</p>
                <p class="text-lg font-black mb-2">{{ $v['value'] }}</p>
                <div class="h-1.5 bg-gray-100 border border-black overflow-hidden">
                    <div class="h-full {{ $v['color'] }}" style="width: {{ $v['pct'] }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Labels --}}
<div>
    <p class="text-[10px] font-black uppercase tracking-widest mb-3">LABELS</p>
    @php
        $labels = [
            ['text' => 'HIGH-PROTEIN',  'class' => 'bg-brand text-white'],
            ['text' => 'LOW-SUGAR',     'class' => 'bg-brand text-white'],
            ['text' => 'BALANCED',      'class' => 'bg-[var(--lime)] text-black'],
            ['text' => 'MEDITERRANEAN', 'class' => 'bg-[var(--lime)] text-black'],
            ['text' => 'DAIRY-FREE',    'class' => 'bg-violet-200 text-black'],
            ['text' => 'PEANUT-FREE',   'class' => 'bg-white text-black'],
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach($labels as $l)
            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 border-2 border-black shadow-[2px_2px_0px_0px_#000] {{ $l['class'] }}">{{ $l['text'] }}</span>
        @endforeach
    </div>
</div>

{{-- Disclaimer --}}
<div class="border-l-4 border-brand pl-4">
    <p class="text-xs italic text-gray-500">Voedingswaarden zijn een schatting per portie. Berekend door Edamam op basis van de ingrediënten.</p>
</div>
