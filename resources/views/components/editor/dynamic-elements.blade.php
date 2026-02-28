@props(['data', 'mode' => 'public'])

@php
    $isEditor = $mode === 'editor';
@endphp

@if($isEditor)
    <!-- Editor Mode: Alpine Rendering -->
    <template
        x-for="element in (getSectionData(getSectionIndex($el.parentNode.closest('[data-section-index]'))).elements || [])"
        :key="element.id">
        <div class="draggable-element cursor-move absolute transition-shadow hover:shadow-xl"
            :class="{'selected ring-2 ring-blue-600 ring-offset-2': activeElement && activeElement.dataset.elementId === element.id}"
            :style="`left: ${element.x}%; top: ${element.y}%; width: ${element.width}px; height: ${element.height}px; z-index: ${(element.styles?.zIndex || 10)}; transform: translate(${(element.translateX || 0)}px, ${(element.translateY || 0)}px)`"
            :data-element-id="element.id" :data-type="element.type"
            :data-label="element.type === 'text' ? 'Text Block' : 'Floating Image'">

            <template x-if="element.type === 'text'">
                <div class="w-full h-full p-1" contenteditable="true" style="outline: none; background: transparent;"
                    :style="{
                                color: element.styles?.color || '#000000',
                                fontSize: element.styles?.fontSize || '16px',
                                fontFamily: element.styles?.fontFamily || 'inherit',
                                fontWeight: element.styles?.fontWeight || '400',
                                textAlign: element.styles?.textAlign || 'left',
                                letterSpacing: (element.styles?.letterSpacing || '0'),
                                lineHeight: element.styles?.lineHeight || '1.2',
                                marginTop: (element.styles?.marginTop || '0'),
                                marginBottom: (element.styles?.marginBottom || '0')
                             }"
                    x-effect="if (document.activeElement !== $el && $el.innerText !== element.content) $el.innerText = element.content"
                    @input="element.content = $el.innerText">
                </div>
            </template>

            <template x-if="element.type === 'image'">
                <img :src="element.content" class="w-full h-full object-cover rounded shadow-lg pointer-events-none">
            </template>
        </div>
    </template>
@else
    <!-- Public Mode: Blade Rendering -->
    @if(isset($data['elements']) && is_array($data['elements']))
        @foreach($data['elements'] as $el)
            @php
                $style = "position: absolute; left: " . ($el['x'] ?? 0) . "%; top: " . ($el['y'] ?? 0) . "%; width: " . ($el['width'] ?? 100) . "px; height: " . ($el['height'] ?? 100) . "px; z-index: " . ($el['styles']['zIndex'] ?? 10) . ";";
                $style .= " transform: translate(" . ($el['translateX'] ?? 0) . "px, " . ($el['translateY'] ?? 0) . "px);";
                if ($el['type'] === 'text') {
                    $style .= " color: " . ($el['styles']['color'] ?? '#000000') . ";";
                    $style .= " font-size: " . ($el['styles']['fontSize'] ?? '16px') . ";";
                    $style .= " font-family: " . ($el['styles']['fontFamily'] ?? 'inherit') . ";";
                    $style .= " font-weight: " . ($el['styles']['fontWeight'] ?? '400') . ";";
                    $style .= " text-align: " . ($el['styles']['textAlign'] ?? 'left') . ";";
                    $style .= " letter-spacing: " . ($el['styles']['letterSpacing'] ?? '0') . ";";
                    $style .= " line-height: " . ($el['styles']['lineHeight'] ?? '1.2') . ";";
                    $style .= " margin-top: " . ($el['styles']['marginTop'] ?? '0') . ";";
                    $style .= " margin-bottom: " . ($el['styles']['marginBottom'] ?? '0') . ";";
                }
            @endphp

            <div style="{{ $style }}">
                @if($el['type'] === 'text')
                    <div class="w-full h-full">{!! $el['content'] !!}</div>
                @elseif($el['type'] === 'image')
                    <img src="{{ $el['content'] }}" class="w-full h-full object-cover rounded shadow-lg pointer-events-none">
                @endif
            </div>
        @endforeach
    @endif
@endif