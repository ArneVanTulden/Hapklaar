<div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
    @foreach($recipe->steps as $step)
        <div class="bg-white border-2 border-black shadow-[3px_3px_0px_0px_#000] flex overflow-hidden min-h-28 cursor-pointer transition-all duration-150 hover:translate-x-[3px] hover:translate-y-[3px] hover:shadow-none"
             :class="activeStep === {{ $step->step_number }} ? '!bg-[var(--lime)] !border-brand !shadow-[3px_3px_0px_0px_var(--brand)]' : ''"
             @click="{{ $step->video_timestamp ? 'jumpTo(\'' . $step->video_timestamp . '\')' : '' }}">
            <div class="relative flex-shrink-0 w-32 sm:w-44 self-stretch border-r-2 border-black">
                <img src="{{ asset('storage/' . $recipe->image_path) }}"
                     alt="Stap {{ $step->step_number }}"
                     class="w-full h-full object-cover">
                <span class="absolute top-1 left-1 text-[6px] font-black uppercase tracking-widest bg-[var(--lime)] border border-black px-1 py-0.5 leading-tight">STAP {{ $step->step_number }} VIDEO</span>
                <button class="absolute inset-0 flex items-center justify-center">
                    <span class="w-10 h-10 rounded-full bg-brand flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                        <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="flex-1 p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="w-7 h-7 rounded-full border-2 border-black flex items-center justify-center text-xs font-black flex-shrink-0">{{ $step->step_number }}</span>
                    <span class="text-[9px] font-black uppercase tracking-widest text-brand cursor-pointer hover:underline">JUMP NAAR</span>
                </div>
                <p class="text-[13px] font-medium leading-snug text-gray-800">{{ $step->description }}</p>
            </div>
        </div>
    @endforeach
</div>
