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
            <input type="password"
                   wire:model="password"
                   placeholder="••••••••"
                   class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-2">BEVESTIG WACHTWOORD</label>
            <input type="password"
                   wire:model="password_confirmation"
                   placeholder="••••••••"
                   class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors">
        </div>

        <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-brand text-white text-[13px] font-black uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 disabled:opacity-60">
            <span wire:loading.remove wire:target="submitReset">WACHTWOORD INSTELLEN &nbsp;→</span>
            <span wire:loading wire:target="submitReset">LADEN...</span>
        </button>

    </form>
</div>
