<img src="{{ $settings['hotel_logo'] ?? '/images/branding/logo.png' }}" 
    alt="{{ $settings['hotel_name'] ?? 'Hotel Logo' }}" 
    {{ $attributes->merge(['class' => 'h-20 w-auto object-contain']) }}
    style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
