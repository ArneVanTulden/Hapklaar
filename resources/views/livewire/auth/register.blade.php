<div>
    <h1 class="text-4xl font-black uppercase mb-5 leading-tight">ACCOUNT<br>AANMAKEN</h1>

    <form wire:submit="register" class="space-y-3">

        {{-- Gebruikersnaam --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-1">GEBRUIKERSNAAM</label>
            <input type="text"
                   wire:model="username"
                   placeholder="student_chef_2024"
                   class="w-full border-2 border-black px-4 py-2.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors @error('username') border-red-500 @enderror">
            @error('username')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-1">E-MAILADRES</label>
            <input type="email"
                   wire:model="email"
                   placeholder="jouw@email.nl"
                   class="w-full border-2 border-black px-4 py-2.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-1">WACHTWOORD</label>
            <div class="relative">
                <input type="password"
                       wire:model="password"
                       placeholder="••••••••"
                       class="w-full border-2 border-black px-4 py-2.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors pr-12 @error('password') border-red-500 @enderror">
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-1">HERHAAL WACHTWOORD</label>
            <div class="relative">
                <input type="password"
                       wire:model="password_confirmation"
                       placeholder="••••••••"
                       class="w-full border-2 border-black px-4 py-2.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors pr-12">
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-brand text-white text-[13px] font-black uppercase italic tracking-widest py-3 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 disabled:opacity-60">
            <span wire:loading.remove wire:target="register">ACCOUNT AANMAKEN →</span>
            <span wire:loading wire:target="register">LADEN...</span>
        </button>

    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-4 my-3">
        <div class="flex-1 h-px bg-gray-300"></div>
        <span class="text-xs font-bold text-gray-400">— OF —</span>
        <div class="flex-1 h-px bg-gray-300"></div>
    </div>

    {{-- Login link --}}
    <a href="{{ route('login') }}"
       class="block w-full text-center text-[11px] font-black uppercase tracking-widest py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 no-underline text-black">
        INLOGGEN
    </a>

    {{-- Terms --}}
    <p class="text-center text-[10px] text-gray-400 mt-3 leading-relaxed">
        Door je aan te melden ga je akkoord met onze
        <a href="#" class="underline hover:text-brand transition-colors">Terms</a>
        &amp;
        <a href="#" class="underline hover:text-brand transition-colors">Privacy Policy</a>.
    </p>
</div>
