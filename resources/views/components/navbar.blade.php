<header class="border-b-2 border-black relative z-40" x-data="{ mobileOpen: false }">

    {{-- MOBILE nav --}}
    <nav class="md:hidden flex items-center justify-between px-4 bg-white" style="height: 52px;">
        <button type="button"
                @click="mobileOpen = !mobileOpen"
                class="flex items-center justify-center w-9 h-9 border-2 border-black bg-white"
                aria-label="Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <a href="{{ route('home') }}"
           class="text-xl font-black italic text-brand no-underline">
            HAPKLAAR
        </a>

        @auth
            <a href="{{ route('profiel') }}"
               class="flex items-center justify-center w-9 h-9 border-2 border-black bg-white"
               aria-label="Profiel">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/>
                    <path stroke-linecap="round" d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="text-[10px] font-black uppercase tracking-widest text-white bg-[var(--pink)] no-underline px-3 py-2 border-2 border-black shadow-[3px_3px_0px_0px_#000]">
                INLOGGEN
            </a>
        @endauth
    </nav>

    {{-- MOBILE menu (dropdown) --}}
    <div x-show="mobileOpen"
         x-transition
         class="md:hidden border-t-2 border-b-2 border-black bg-white absolute top-[52px] left-0 right-0 z-50 shadow-[0_8px_0_-2px_#000]"
         style="display: none;">
        <ul class="flex flex-col list-none m-0 p-0">
            @foreach([
                ['home','HOME'],
                ['ontdekken','ONTDEKKEN'],
                ['mijn-keuken','MIJN KEUKEN'],
                ['mijn-voorraad','VOORRAAD'],
                ['boodschappen','BOODSCHAPPEN'],
                ['profiel','PROFIEL'],
            ] as [$r,$label])
                <li>
                    <a href="{{ route($r) }}"
                       @class([
                           'flex items-center w-full px-5 py-4 text-[12px] font-bold uppercase tracking-widest text-black no-underline border-b border-gray-100',
                           'bg-[var(--lime)]' => request()->routeIs($r),
                       ])>{{ $label }}</a>
                </li>
            @endforeach
            @auth
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-5 py-3 text-[12px] font-black uppercase tracking-widest text-[var(--pink)]">
                            LOGOUT
                        </button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>

    {{-- DESKTOP nav: 3-column grid keeps links truly centered --}}
    <nav class="hidden md:grid items-center px-6 bg-white"
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
                <a href="{{ route('mijn-voorraad') }}"
                   @class([
                       'text-[11px] font-bold uppercase tracking-widest text-black no-underline',
                       'border-2 border-black rounded-full px-3 py-1 leading-none bg-[var(--lime)] shadow-[4px_4px_0px_0px_#000]' => request()->routeIs('mijn-voorraad'),
                   ])>
                    VOORRAAD
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
    <div class="h-0.5 bg-black"></div>
    <div class="h-1 bg-brand"></div>

    {{-- Ticker --}}
    <div
        class="bg-[var(--lime)] overflow-hidden"
        x-data="{
            init() {
                const build = () => {
                    const root = this.$refs.root;
                    const track = this.$refs.track;
                    const group = this.$refs.group;
                    const item = this.$refs.item;

                    if (!root || !track || !group || !item) {
                        return;
                    }

                    track.querySelectorAll('[data-ticker-clone]').forEach((node) => node.remove());
                    group.querySelectorAll('[data-ticker-item]').forEach((node, index) => {
                        if (index > 0) {
                            node.remove();
                        }
                    });

                    const containerWidth = root.clientWidth;
                    let groupWidth = group.scrollWidth;
                    let safety = 0;

                    while (groupWidth < containerWidth && safety < 20) {
                        group.appendChild(item.cloneNode(true));
                        groupWidth = group.scrollWidth;
                        safety += 1;
                    }

                    const clone = group.cloneNode(true);
                    clone.setAttribute('data-ticker-clone', 'true');
                    track.appendChild(clone);

                    const pixelsPerSecond = 14;
                    const durationSeconds = Math.max(85, group.scrollWidth / pixelsPerSecond);
                    track.style.setProperty('--ticker-duration', `${durationSeconds}s`);
                    track.style.animationDuration = `${durationSeconds}s`;
                };

                this.$nextTick(() => build());
                const resizeObserver = new ResizeObserver(() => build());
                resizeObserver.observe(this.$refs.root);
                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) {
                        build();
                    }
                });
            }
        }"
        x-init="init()"
        x-ref="root"
    >
        <div class="ticker-track flex w-max animate-ticker py-[7px]" x-ref="track">
            @php
                $text = \App\Support\Settings::get('marquee_text', 'HAPKLAAR &bull; &nbsp;&nbsp;&nbsp;');
            @endphp
            <div class="ticker-group flex w-max" x-ref="group">
                <span class="ticker-item text-[11px] font-bold uppercase tracking-widest text-black whitespace-nowrap" x-ref="item" data-ticker-item>{!! $text !!}</span>
            </div>
        </div>
    </div>

</header>
