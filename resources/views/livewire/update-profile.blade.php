<div>
    <h3 class="text-sm font-black uppercase tracking-widest mb-5">JOUW PROFIEL</h3>

    <input type="file" id="avatar-input" wire:model="avatar" accept="image/*" class="hidden">

    @error('avatar') <p class="text-brand text-[9px] font-black uppercase mb-3">{{ $message }}</p> @enderror

    <form wire:submit="save">
        <div class="mb-4">
            <label class="block text-[8px] font-black uppercase tracking-widest text-brand mb-1.5">GEBRUIKERSNAAM</label>
            <input type="text"
                   wire:model="username"
                   class="w-full border-2 border-black px-3 py-2.5 text-sm font-medium outline-none focus:border-brand transition-colors">
            @error('username') <p class="text-brand text-[9px] font-black uppercase mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-5">
            <label class="block text-[8px] font-black uppercase tracking-widest text-brand mb-1.5">BIO</label>
            <textarea rows="3"
                      wire:model="bio"
                      class="w-full border-2 border-black px-3 py-2.5 text-sm font-medium outline-none focus:border-brand transition-colors resize-none"></textarea>
            @error('bio') <p class="text-brand text-[9px] font-black uppercase mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60 cursor-not-allowed"
                class="w-full bg-brand text-white text-[11px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
            <span wire:loading.remove>OPSLAAN</span>
            <span wire:loading>OPSLAAN...</span>
        </button>
    </form>
</div>
