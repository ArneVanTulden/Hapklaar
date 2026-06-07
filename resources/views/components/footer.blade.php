<footer class="border-t-2 border-brand bg-black">

    {{-- Desktop: 3-col grid. Mobile: stacked --}}
    <div class="hidden md:grid items-center px-6 py-5"
         style="grid-template-columns: 1fr auto 1fr;">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
            class="text-2xl font-black italic text-[var(--lime)] no-underline">
            HAPKLAAR
        </a>

        {{-- Links (center) --}}
        <ul class="flex items-center gap-7 list-none m-0 p-0">
            <li><a href="{{ route('privacy') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline hover:text-white transition-colors">PRIVACY</a></li>
            <li><a href="{{ route('terms') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline hover:text-white transition-colors">TERMS</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline hover:text-white transition-colors">SUPPORT</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline hover:text-white transition-colors">CONTACT</a></li>
        </ul>

        {{-- Copyright (right) --}}
        <div class="flex justify-end">
            <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                &copy; {{ date('Y') }} HAPKLAAR
            </span>
        </div>

    </div>

    {{-- Mobile stacked --}}
    <div class="md:hidden flex flex-col items-center gap-5 px-6 py-8 text-center">
        <a href="{{ route('home') }}"
           class="text-2xl font-black italic text-[var(--lime)] no-underline">
            HAPKLAAR
        </a>

        <ul class="flex items-center justify-center gap-5 list-none m-0 p-0 flex-wrap">
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline">PRIVACY</a></li>
            <li><a href="{{ route('terms') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline">TERMS</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline">SUPPORT</a></li>
            <li><a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 no-underline">CONTACT</a></li>
        </ul>

        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
            &copy; {{ date('Y') }} HAPKLAAR
        </span>
    </div>

</footer>
