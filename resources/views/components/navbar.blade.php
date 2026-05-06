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
            <li>
                <a href="{{ route('ontdekken') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('ontdekken'),
                   ])>
                    ONTDEKKEN
                </a>
            </li>
            <li>
                <a href="{{ route('mijn-keuken') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('mijn-keuken'),
                   ])>
                    MIJN KEUKEN
                </a>
            </li>
            <li>
                <a href="{{ route('boodschappen') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('boodschappen'),
                   ])>
                    BOODSCHAPPEN
                </a>
            </li>
            <li>
                <a href="{{ route('profiel') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('profiel'),
                   ])>
                    PROFIEL
                </a>
            </li>
        </ul>

        {{-- Login button (right column) --}}
        <div class="flex justify-end">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center justify-center w-9 h-9 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 bg-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"/>
                            <path stroke-linecap="round" d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-44 bg-white border-2 border-black shadow-[4px_4px_0px_0px_#000] z-50"
                         style="display: none;">
                        <a href="{{ route('profiel') }}"
                           class="flex items-center gap-2 px-4 py-3 text-[11px] font-black uppercase tracking-widest text-black no-underline hover:bg-[var(--lime)] border-b border-gray-100 transition-colors">
                            PROFIEL
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}"
                           class="flex items-center gap-2 px-4 py-3 text-[11px] font-black uppercase tracking-widest text-black no-underline hover:bg-[var(--lime)] border-b border-gray-100 transition-colors">
                            ADMIN
                        </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 text-[11px] font-black uppercase tracking-widest text-[var(--pink)] hover:bg-[var(--pink-soft)] transition-colors">
                                LOGOUT
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
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
