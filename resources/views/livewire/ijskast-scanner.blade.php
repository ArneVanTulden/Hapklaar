{{-- ============================================================
     IJSKAST SCANNER
============================================================ --}}
<div
    x-data="{
        drag: false,
        triggerCamera() { this.$refs.cameraInput.click(); },
        triggerGallery() { this.$refs.galleryInput.click(); },
    }"
    @dragover.prevent="drag = true"
    @dragleave.prevent="drag = false"
    @drop.prevent="
        drag = false;
        if ($event.dataTransfer.files.length) {
            const file = $event.dataTransfer.files[0];
            if (!file.type.startsWith('image/')) {
                $dispatch('invalid-file-type');
                return;
            }
            const dt = new DataTransfer();
            dt.items.add(file);
            $refs.cameraInput.files = dt.files;
            $refs.cameraInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    "
    @invalid-file-type.window="$wire.set('error', 'Alleen afbeeldingen (JPG, PNG, WebP) zijn toegestaan.')"
    class="relative flex flex-col"
>
    {{-- ============================================================
         FOTO PANEEL
    ============================================================ --}}
    <div class="relative bg-white border-[3px] border-black shadow-[6px_6px_0px_0px_#000] overflow-hidden flex-1 flex flex-col"
         :class="{ 'shadow-[6px_6px_0px_0px_var(--lime)]': drag }">

        {{-- Photo area --}}
        <div class="relative flex-1" style="aspect-ratio: 4/3;">

            {{-- SCAN LOADING OVERLAY (alleen tijdens scan-request) --}}
            <div wire:loading.flex wire:target="scan"
                 class="absolute inset-0 z-40 bg-black/85 hidden flex-col items-center justify-center px-6">
                <div class="w-16 h-16 mb-4">
                    <svg class="w-full h-full animate-spin text-[var(--lime)]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
                <p class="text-white text-[14px] font-black italic uppercase tracking-wide">AI snuffelt door je koelkast...</p>
                <div class="flex gap-1.5 mt-3">
                    <span class="w-2 h-2 bg-[var(--lime)] rounded-full animate-bounce [animation-delay:0ms]"></span>
                    <span class="w-2 h-2 bg-[var(--lime)] rounded-full animate-bounce [animation-delay:150ms]"></span>
                    <span class="w-2 h-2 bg-[var(--lime)] rounded-full animate-bounce [animation-delay:300ms]"></span>
                </div>
                <p class="text-white/60 text-[9px] font-black uppercase tracking-widest mt-3">Dit kan 5-15 sec duren</p>
            </div>

            @if($photo)
                <img src="{{ $photo->temporaryUrl() }}"
                     alt="Scan"
                     class="absolute inset-0 w-full h-full object-cover">

                <button wire:click.stop="clearPhoto"
                        wire:loading.attr="disabled" wire:target="scan"
                        type="button"
                        class="absolute top-3 right-3 w-8 h-8 bg-white border-2 border-black flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors z-20 shadow-[2px_2px_0px_0px_#000] disabled:opacity-0"
                        title="Wissen">
                    <span class="text-[16px] font-black leading-none">×</span>
                </button>
            @else
                <button type="button"
                        @click="triggerCamera()"
                        class="absolute inset-0 flex flex-col items-center justify-center bg-[var(--pink-soft)] bg-[radial-gradient(circle,rgba(0,0,0,0.18)_1.2px,transparent_1.6px)] bg-[length:9px_9px] hover:bg-white transition-colors group"
                        :class="{ '!bg-[var(--lime)]/60': drag }">

                    <div class="w-20 h-20 rounded-full bg-brand border-[3px] border-black shadow-[4px_4px_0px_0px_#000] flex items-center justify-center mb-4 group-hover:translate-x-[1px] group-hover:translate-y-[1px] group-hover:shadow-[2px_2px_0px_0px_#000] transition-all">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>

                    <p class="text-[14px] font-black italic uppercase tracking-wide">
                        <span x-show="!drag">Klik · sleep · kies</span>
                        <span x-show="drag" x-cloak>Loslaten!</span>
                    </p>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-black/50 mt-1">JPG · PNG · MAX 20MB</p>
                </button>
            @endif
        </div>

        {{-- Error strip --}}
        @if($error || $errors->has('photo'))
            <div class="border-t-[3px] border-black bg-red-500 text-white px-4 py-2 text-[10px] font-black uppercase tracking-widest">
                ⚠ {{ $error ?? $errors->first('photo') }}
            </div>
        @endif
    </div>

    {{-- ============================================================
         CONTROL DECK
    ============================================================ --}}
    <div class="mt-4 flex items-stretch gap-2">

        <button type="button"
                @click="triggerGallery()"
                wire:loading.attr="disabled" wire:target="scan"
                class="w-12 flex items-center justify-center bg-white border-[3px] border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] disabled:opacity-40 transition-all duration-75"
                title="Uit galerij">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </button>

        <button type="button"
                @click="triggerCamera()"
                wire:loading.attr="disabled" wire:target="scan"
                class="w-12 flex items-center justify-center bg-[var(--lime)] border-[3px] border-black shadow-[3px_3px_0px_0px_#000] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[2px_2px_0px_0px_#000] disabled:opacity-40 transition-all duration-75"
                title="Maak foto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>

        <button wire:click="scan"
                wire:loading.attr="disabled" wire:target="scan"
                type="button"
                @disabled(!$photo)
                class="flex-1 bg-brand text-white font-black uppercase italic tracking-widest border-[3px] border-black shadow-[4px_4px_0px_0px_#000] disabled:opacity-40 disabled:cursor-not-allowed hover:enabled:translate-x-[1px] hover:enabled:translate-y-[1px] hover:enabled:shadow-[2px_2px_0px_0px_#000] transition-all duration-75 flex items-center justify-center gap-2 text-[14px] py-3">
            <span wire:loading.remove wire:target="scan">SCAN MIJN ZOOI →</span>
            <span wire:loading.flex wire:target="scan" class="hidden items-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                BEZIG...
            </span>
        </button>
    </div>

    {{-- Hidden inputs --}}
    <input type="file" wire:model="photo" x-ref="cameraInput"
           accept="image/*" capture="environment" class="hidden">
    <input type="file" wire:model="photo" x-ref="galleryInput"
           accept="image/*" class="hidden">
</div>
