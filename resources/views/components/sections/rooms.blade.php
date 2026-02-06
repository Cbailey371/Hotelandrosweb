@props(['data', 'rooms'])

<div class="max-w-[1440px] mx-auto px-4 md:px-8">
    <!-- Habitaciones Section -->
    <section id="habitaciones" class="scroll-mt-32 mb-24">
        <div class="flex flex-col items-center text-center mb-16">
            <div
                class="px-4 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                {!! app()->getLocale() == 'es' ? ($data['rooms_badge_es'] ?? '') : ($data['rooms_badge_en'] ?? '') !!}
            </div>
            <div class="text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-800 dark:text-white">
                {!! app()->getLocale() == 'es' ? ($data['rooms_title_es'] ?? '') : ($data['rooms_title_en'] ?? '') !!}
            </div>
            <div class="w-20 h-1.5 bg-primary rounded-full mb-6"></div>
            <div class="text-secondary max-w-2xl text-lg">
                {!! app()->getLocale() == 'es' ? ($data['rooms_description_es'] ?? '') : ($data['rooms_description_en'] ?? '') !!}
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            @if(isset($rooms))
                @foreach($rooms as $room)
                    <div
                        class="w-full max-w-[400px] bg-white dark:bg-[#0b0c11] rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 group flex flex-col transition-all hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2">
                        <div class="h-72 overflow-hidden relative">
                            <div class="w-full h-full bg-center bg-cover transition-transform duration-1000 group-hover:scale-110"
                                style='background-image: url("{{ $room->image }}");' loading="lazy"></div>
                            <div
                                class="absolute top-4 right-4 px-5 py-3 bg-white/95 backdrop-blur rounded-2xl shadow-xl flex items-center gap-1">
                                <span class="text-green-600 font-black text-xl">${{ number_format($room->price, 0) }}</span>
                                <span class="text-[10px] font-bold text-[#4c739a] uppercase">{{ __('/noche') }}</span>
                            </div>
                        </div>
                        <div class="p-10 flex-1 flex flex-col">
                            <h4 class="text-[28px] font-black mb-2 text-slate-800 dark:text-white leading-tight">
                                {{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}
                            </h4>
                            <p class="text-sm font-medium text-[#4c739a] dark:text-slate-400 mb-8">
                                {{ app()->getLocale() == 'es' ? $room->description_es : $room->description_en }}
                            </p>

                            <div class="flex flex-wrap gap-2 mb-8">
                                @foreach(array_slice($room->amenities ?? [], 0, 3) as $amenity)
                                    <div
                                        class="flex items-center gap-2 px-4 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl text-[10px] font-bold text-[#475569] dark:text-slate-300 uppercase tracking-widest">
                                        <span class="material-symbols-outlined text-sm text-green-600 font-bold">check</span>
                                        {{ $amenity }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-auto pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600 text-2xl">group</span>
                                        <span class="text-sm font-black text-slate-800 dark:text-white">{{ $room->capacity }}
                                            {{ __('Pers.') }}</span>
                                    </div>
                                </div>

                                <button
                                    onclick="openBookingModal('{{ $room->id }}', '{{ app()->getLocale() == 'es' ? $room->name_es : $room->name_en }}')"
                                    class="w-full py-4 bg-green-600 text-white text-sm font-black rounded-2xl hover:bg-green-700 transition-all uppercase tracking-widest shadow-lg shadow-green-900/10">
                                    {{ __('Reservar Ahora') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-10 bg-slate-50 w-full rounded-2xl">
                    <p class="text-slate-500 font-bold">No rooms data available via Editor yet.</p>
                </div>
            @endif
        </div>
    </section>
</div>