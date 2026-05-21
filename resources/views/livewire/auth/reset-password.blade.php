<div>
    @error('email')
        <div class="bg-red-50 border-2 border-red-500 p-4 mb-6">
            <p class="text-sm font-bold text-red-700 uppercase tracking-wide">{{ $message }}</p>
        </div>
    @enderror

    <form wire:submit="submitReset" class="space-y-5">

        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-2">E-MAILADRES</label>
            <input type="email"
                   wire:model="email"
                   placeholder="jouw@email.nl"
                   class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors @error('email') border-red-500 @enderror">
        </div>

        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-2">NIEUW WACHTWOORD</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'"
                       wire:model="password"
                       placeholder="••••••••"
                       class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors pr-12 @error('password') border-red-500 @enderror">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-2">BEVESTIG WACHTWOORD</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'"
                       wire:model="password_confirmation"
                       placeholder="••••••••"
                       class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors pr-12">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-brand text-white text-[13px] font-black uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 disabled:opacity-60">
            <span wire:loading.remove wire:target="submitReset">WACHTWOORD INSTELLEN &nbsp;→</span>
            <span wire:loading wire:target="submitReset">LADEN...</span>
        </button>

    </form>
</div>
