<div class="relative">
    {{-- GEEN STRESS badge --}}
    <div class="absolute -top-3 -left-2 z-10 bg-[var(--lime)] border-2 border-black px-3 py-1 text-[9px] font-black uppercase tracking-widest shadow-[2px_2px_0px_0px_#000]">
        GEEN STRESS!
    </div>

    <div class="bg-white border-2 border-black shadow-[5px_5px_0px_0px_#000]">
        {{-- Step label --}}
        <div class="bg-[var(--lime)] border-b-2 border-black px-4 py-2">
            <span class="text-[9px] font-black uppercase tracking-widest">STAP 1: TREK DIE DEUR OPEN</span>
        </div>

        {{-- Drop zone --}}
        <div class="flex flex-col items-center justify-center py-14 px-8">
            <div class="relative mb-6">
                {{-- Dashed circle --}}
                <div class="w-44 h-44 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover rounded-full">
                    @else
                        <label for="scanner-photo-upload"
                               class="w-28 h-28 rounded-full bg-brand border-2 border-black shadow-[4px_4px_0px_0px_#000] flex items-center justify-center hover:shadow-[2px_2px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] transition-all duration-75 cursor-pointer">
                            @if($isScanning)
                                <svg class="w-10 h-10 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                            @else
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            @endif
                        </label>
                    @endif
                </div>

                @if($isScanning)
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 whitespace-nowrap bg-brand text-white text-[8px] font-black uppercase tracking-widest px-2 py-0.5 border border-black">
                        AI SCANT...
                    </div>
                @endif
            </div>

            {{-- Error --}}
            @if($error)
                <p class="text-[10px] font-black uppercase text-red-600 mb-3 text-center tracking-widest">{{ $error }}</p>
            @else
                <p class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-6">
                    {{ $photo ? 'FOTO KLAAR! HIT SCAN-IT →' : 'DROP JE FOTO HIER' }}
                </p>
            @endif

            {{-- Hidden file inputs --}}
            <input type="file" id="scanner-photo-upload" wire:model="photo"
                   accept="image/*" capture="environment" class="hidden">
            <input type="file" id="scanner-photo-gallery" wire:model="photo"
                   accept="image/*" class="hidden">

            {{-- Buttons --}}
            <div class="flex gap-3 w-full">
                <label for="scanner-photo-upload"
                       class="flex-1 bg-brand text-white text-[11px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 text-center cursor-pointer">
                    MAAK FOTO
                </label>
                <label for="scanner-photo-gallery"
                       class="flex-1 bg-[var(--lime)] text-black text-[11px] font-black uppercase tracking-widest py-3.5 border-2 border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 text-center cursor-pointer">
                    UPLOAD
                </label>
            </div>
        </div>
    </div>

    {{-- SCAN-IT badge bottom-right --}}
    <div class="absolute -bottom-3 -right-3 z-10">
        <button wire:click="scan"
                @disabled(!$photo || $isScanning)
                class="bg-brand text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 border-2 border-black shadow-[3px_3px_0px_0px_#000] disabled:opacity-40 disabled:cursor-not-allowed hover:enabled:translate-x-[1px] hover:enabled:translate-y-[1px] hover:enabled:shadow-[2px_2px_0px_0px_#000] transition-all duration-75">
            {{ $isScanning ? 'BEZIG...' : 'SCAN-IT' }}
        </button>
    </div>
</div>
