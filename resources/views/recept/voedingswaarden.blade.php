@php
    $n        = $recipe->nutritionInfo;
    $kcal     = $n ? (int) round($n->calories)   : 0;
    $protein  = $n ? (int) round($n->protein)    : 0;
    $carbs    = $n ? (int) round($n->carbs)       : 0;
    $fat      = $n ? (int) round($n->fat)         : 0;
    $sugar    = $n ? (int) round($n->sugar)       : 0;
    $fiber    = $n ? (int) round($n->fiber)       : 0;
    $sodium   = $n ? (int) round($n->sodium)      : 0; // mg
    $potassium= $n ? (int) round($n->potassium)  : 0; // mg
    $iron     = $n ? round((float)$n->iron, 1)   : 0; // mg
    $vitA     = $n ? (int) round($n->vitamin_a)  : 0; // μg
    $vitC     = $n ? (int) round($n->vitamin_c)  : 0; // mg

    $kcalPct     = min(100, (int) round($kcal     / 2000  * 100));
    $proteinPct  = min(100, (int) round($protein  / 50    * 100));
    $carbsPct    = min(100, (int) round($carbs    / 260   * 100));
    $fatPct      = min(100, (int) round($fat      / 78    * 100));
    $sodiumPct   = min(100, (int) round($sodium   / 2300  * 100));
    $potassiumPct= min(100, (int) round($potassium/ 3500  * 100));
    $ironPct     = min(100, (int) round($iron     / 14    * 100));
    $vitAPct     = min(100, (int) round($vitA     / 800   * 100));
    $vitCPct     = min(100, (int) round($vitC     / 80    * 100));
    $fiberPct    = min(100, (int) round($fiber    / 30    * 100));

    $sodiumHigh  = $sodiumPct >= 60;
    $sodiumDisplay = $sodium >= 1000
        ? number_format($sodium / 1000, 1) . 'g'
        : $sodium . 'mg';
@endphp

{{-- Calorieën --}}
<div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-6">
    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">CALORIEËN PER PORTIE</p>
    <p class="text-4xl md:text-5xl font-black italic text-brand mb-3">{{ $kcal }} KCAL</p>
    <div class="h-3 bg-gray-100 border border-black overflow-hidden mb-1">
        <div class="h-full bg-[var(--lime)]" style="width: {{ $kcalPct }}%"></div>
    </div>
    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ $kcalPct }}% VAN JE DAGELIJKSE INNAME</p>
</div>

{{-- Macronutriënten --}}
<div>
    <p class="text-[10px] font-black uppercase tracking-widest mb-3">MACRONUTRIËNTEN</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">EIWITTEN</p>
            <p class="text-2xl font-black mb-2">{{ $protein }}G</p>
            <div class="h-2 bg-gray-100 border border-black overflow-hidden mb-2">
                <div class="h-full bg-brand" style="width: {{ $proteinPct }}%"></div>
            </div>
        </div>
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">KOOLHYDRATEN</p>
            <p class="text-2xl font-black mb-2">{{ $carbs }}G</p>
            <div class="h-2 bg-gray-100 border border-black overflow-hidden mb-2">
                <div class="h-full bg-[var(--lime)]" style="width: {{ $carbsPct }}%"></div>
            </div>
            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">WAARVAN SUIKERS: {{ $sugar }}G</p>
        </div>
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] p-4">
            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">VETTEN</p>
            <p class="text-2xl font-black mb-2">{{ $fat }}G</p>
            <div class="h-2 bg-gray-100 border border-black overflow-hidden mb-2">
                <div class="h-full bg-violet-400" style="width: {{ $fatPct }}%"></div>
            </div>
            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">VEZELS: {{ $fiber }}G</p>
        </div>
    </div>
</div>

{{-- Vitaminen & Mineralen --}}
<div>
    <p class="text-[10px] font-black uppercase tracking-widest mb-3">VITAMINEN & MINERALEN</p>
    @php
        $minerals = [
            ['label' => 'VITAMINE A', 'value' => $vitA . 'μg',       'pct' => $vitAPct,      'color' => 'bg-[var(--lime)]'],
            ['label' => 'VITAMINE C', 'value' => $vitC . 'mg',        'pct' => $vitCPct,      'color' => 'bg-[var(--lime)]'],
            ['label' => 'IJZER',      'value' => $iron . 'mg',        'pct' => $ironPct,      'color' => 'bg-[var(--lime)]'],
            ['label' => 'KALIUM',     'value' => $potassium . 'mg',   'pct' => $potassiumPct, 'color' => 'bg-[var(--lime)]'],
            ['label' => 'NATRIUM',    'value' => $sodiumDisplay,       'pct' => $sodiumPct,    'color' => $sodiumHigh ? 'bg-brand' : 'bg-[var(--lime)]'],
            ['label' => 'VEZELS',     'value' => $fiber . 'g',        'pct' => $fiberPct,     'color' => 'bg-[var(--lime)]'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($minerals as $v)
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

{{-- Disclaimer --}}
<div class="border-l-4 border-brand pl-4">
    <p class="text-xs italic text-gray-500">Voedingswaarden zijn een schatting per portie op basis van de ingrediënten. Bron: USDA FoodData Central.</p>
</div>
