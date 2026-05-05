<header class="border-b-2 border-black">

    {{-- Main nav: 3-column grid keeps links truly centered --}}
    <nav class="grid items-center px-6 bg-white"
         style="grid-template-columns: 1fr auto 1fr; height: 52px;">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="text-2xl font-black italic text-brand no-underline">
            HAPKLAAR
        </a>

        {{-- Nav links (center column) --}}
        <ul class="flex items-center gap-7 list-none m-0 p-0">
            <li>
                <a href="{{ route('home') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('home'),
                   ])>
                    HOME
                </a>
            </li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-black no-underline">ONTDEKKEN</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-black no-underline">MIJN KEUKEN</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-black no-underline">BOODSCHAPPEN</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-black no-underline">PROFIEL</a></li>
        </ul>

        {{-- Login button (right column) --}}
        <div class="flex justify-end">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="text-[11px] font-black uppercase tracking-widest text-white bg-[var(--pink)] no-underline px-5 py-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                    DASHBOARD
                </a>
            @else
                <a href="#"
                   class="text-[11px] font-black uppercase tracking-widest text-white bg-[var(--pink)] no-underline px-5 py-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">
                    LOGIN
                </a>
            @endauth
        </div>

    </nav>

    {{-- Pink separator line --}}
    <div class="h-1 bg-brand"></div>

    {{-- Ticker --}}
    <div class="bg-[var(--lime)] overflow-hidden mt-2">
        <div class="flex w-max animate-ticker py-[7px]">
            @php
                $text = 'ELKE KATER PASTA &bull; KOOK VOOR &euro;2 PER PERSOON &bull; GEEN STRESS GEWOON VRETEN &bull; STUDENTENDEALS: GRATIS BIER BIJ BESTELLING &bull; &nbsp;&nbsp;&nbsp;';
            @endphp
            <span class="text-[11px] font-bold uppercase tracking-widest text-black whitespace-nowrap">{!! $text !!}</span>
            <span class="text-[11px] font-bold uppercase tracking-widest text-black whitespace-nowrap">{!! $text !!}</span>
        </div>
    </div>

</header>
