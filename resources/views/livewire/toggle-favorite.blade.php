<button wire:click.stop="toggle"
        aria-label="{{ $isFavorited ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}"
        class="{{ $compact ? 'w-7 h-7 shadow-[2px_2px_0px_0px_#000]' : 'w-12 h-12 shadow-[3px_3px_0px_0px_#000]' }} rounded-full border-2 border-black flex items-center justify-center hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_#000] transition-all duration-75 {{ $isFavorited ? 'bg-brand' : 'bg-white' }}">
    <svg class="{{ $compact ? 'w-3.5 h-3.5' : 'w-5 h-5' }} {{ $isFavorited ? 'text-white' : 'text-brand' }}"
         fill="{{ $isFavorited ? 'currentColor' : 'none' }}"
         stroke="currentColor"
         stroke-width="2"
         viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
</button>
