<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Procesa una imagen, la convierte a WebP y la guarda en el storage.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param int $quality
     * @param int|null $width
     * @return string
     */
    public static function storeAsWebp($file, $directory = 'uploads', $quality = 100, $width = null)
    {
        // Intentar aumentar recursos para procesar fotos pesadas
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $filename = Str::random(40) . '.webp';
        $path = $directory . '/' . $filename;

        $image = Image::read($file);

        if ($width) {
            $image->scale(width: $width);
        }

        $encoded = $image->toWebp($quality);

        Storage::disk('public')->put($path, (string) $encoded);

        return '/storage/' . $path;
    }
}
