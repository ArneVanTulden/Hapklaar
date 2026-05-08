@props([
    'title' => 'INGREDIËNT TOEVOEGEN',
])

<div x-data="{ open: false }"
     @open-ingredient-modal.window="open = true"
     @close-ingredient-modal.window="open = false"
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    <div class="absolute inset-0 bg-black/60" @click="open = false"></div>

    <div class="relative bg-white border-2 border-black shadow-[8px_8px_0px_0px_var(--pink)] w-full max-w-md z-10"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <button @click="open = false"
                class="absolute -top-4 -right-4 w-10 h-10 rounded-full bg-black text-white flex items-center justify-center text-xl font-black border-2 border-black hover:bg-gray-800 transition-colors z-20">
            ✕
        </button>

        <div class="p-8">
            <h2 class="text-3xl font-black uppercase italic mb-6">{{ $title }}</h2>

            {{ $slot }}
        </div>
    </div>
</div>
