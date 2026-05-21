<div>
    <h1 class="text-3xl md:text-4xl font-black uppercase italic leading-none mb-2">WACHTWOORD VERGETEN?</h1>
    <p class="text-sm text-gray-600 leading-relaxed mb-6">
        Geen stress. Vul je e-mailadres in en we sturen je een link om een nieuw wachtwoord in te stellen. Check ook je spam-folder — soms duikt hij daar op.
    </p>

    @if ($sent)
        <div class="bg-green-50 border-2 border-green-500 p-4 mb-6">
            <p class="text-sm font-bold text-green-700 uppercase tracking-wide">
                We hebben een link gestuurd om je wachtwoord aan te passen.
            </p>
        </div>
    @endif

    <form wire:submit="sendLink" class="space-y-5">

        <div>
            <label class="block text-[9px] font-black uppercase tracking-widest mb-2">E-MAILADRES</label>
            <input type="email"
                   wire:model="email"
                   placeholder="jouw@email.nl"
                   class="w-full border-2 border-black px-4 py-3.5 text-sm font-medium placeholder-gray-300 outline-none focus:border-brand transition-colors @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-600 text-[10px] font-bold mt-1 uppercase tracking-wide">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-brand text-white text-[13px] font-black uppercase tracking-widest py-4 border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 disabled:opacity-60">
            <span wire:loading.remove wire:target="sendLink">STUUR LINK &nbsp;→</span>
            <span wire:loading wire:target="sendLink">LADEN...</span>
        </button>

    </form>

    <div class="flex items-center gap-4 my-6">
        <div class="flex-1 h-px bg-gray-400"></div>
        <span class="text-xs font-bold text-gray-500">OF</span>
        <div class="flex-1 h-px bg-gray-400"></div>
    </div>

    <a href="{{ route('login') }}"
       class="block w-full text-center text-[11px] font-black uppercase tracking-widest py-4 border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 no-underline text-black">
        TERUG NAAR INLOGGEN
    </a>
</div>
