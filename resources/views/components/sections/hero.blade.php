@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
    $editable = $isEditor ? 'contenteditable="true"' : '';
    $clickAction = $isEditor ? '@click.stop="selectElement($el)"' : '';
    
    // Default values
    $overlayOpacity = $data['overlay_opacity'] ?? 50;
    $bgBlur = $data['bg_blur'] ?? 0;
    $paddingY = $data['padding_y'] ?? 100; // Default padding
    $containerWidth = ($data['container_width'] ?? 'boxed') === 'full' ? 'max-w-full px-10' : 'container mx-auto px-6';
    $bgImage = $data['bg_image'] ?? '/images/hero.jpg';
@endphp

<div class="relative mb-8" id="inicio" 
     @click="{{ $isEditor ? 'selectSection($index)' : '' }}"
     style="padding-top: {{ $paddingY }}px; padding-bottom: {{ $paddingY }}px;">
    
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
         <div class="w-full h-full bg-cover bg-center transition-all duration-300 {{ $isEditor ? 'cursor-pointer hover:ring-4 ring-blue-500/50' : '' }}"
              @click="{{ $isEditor ? 'selectImage(\''.$bgImage.'\')' : '' }}"
              data-type="image"
              data-field="bg_image"
              style="background-image: url('{{ $bgImage }}'); filter: blur({{ $bgBlur }}px);"></div>
    </div>

    <!-- Overlay Layer -->
    <div class="absolute inset-0 z-0 bg-black transition-opacity duration-300 pointer-events-none"
         style="opacity: {{ $overlayOpacity / 100 }};"></div>

    <!-- Content Container -->
    <div class="{{ $containerWidth }} relative z-10 flex flex-col items-center justify-center text-center h-full min-h-[400px]"
         style="gap: {{ $data['gap'] ?? 24 }}px;">

            <!-- Hero Title -->
            <div class="ql-editor hero-content leading-tight drop-shadow-2xl hero-no-filter text-white text-5xl md:text-7xl {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!}
                data-field="title_{{ app()->getLocale() }}"
                data-label="Hero Title"
                {!! $clickAction !!}
                style="font-family: inherit; font-weight: normal; color: {{ $data['title_'.app()->getLocale().'_color'] ?? 'inherit' }}; font-size: {{ $data['title_'.app()->getLocale().'_fontSize'] ?? 'inherit' }}; font-family: {{ $data['title_'.app()->getLocale().'_fontFamily'] ?? 'inherit' }}; text-align: {{ $data['title_'.app()->getLocale().'_textAlign'] ?? 'center' }};">
                {!! app()->getLocale() == 'es' ? ($data['title_es'] ?? '') : ($data['title_en'] ?? '') !!}
            </div>

            <!-- Hero Subtitle -->
            <div class="ql-editor hero-content max-w-3xl mx-auto leading-relaxed drop-shadow-xl hero-no-filter text-white text-xl md:text-2xl {{ $isEditor ? 'hover:ring-2 ring-blue-500 rounded px-2' : '' }}"
                {!! $editable !!}
                data-field="subtitle_{{ app()->getLocale() }}"
                data-label="Hero Subtitle"
                {!! $clickAction !!}
                style="font-family: inherit; color: {{ $data['subtitle_'.app()->getLocale().'_color'] ?? 'inherit' }}; font-size: {{ $data['subtitle_'.app()->getLocale().'_fontSize'] ?? 'inherit' }}; font-family: {{ $data['subtitle_'.app()->getLocale().'_fontFamily'] ?? 'inherit' }}; text-align: {{ $data['subtitle_'.app()->getLocale().'_textAlign'] ?? 'center' }};">
                {!! app()->getLocale() == 'es' ? ($data['subtitle_es'] ?? '') : ($data['subtitle_en'] ?? '') !!}
            </div>
            
            <div class="mt-8">
                 <a href="#habitaciones"
                    class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-xl hover:bg-white hover:text-primary transition-all">
                    {{ __('Descubrir Habitaciones') }}
                </a>
            </div>
    </div>
</div>