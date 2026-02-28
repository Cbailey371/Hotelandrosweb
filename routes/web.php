<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicBookingController;

// Web Pública
Route::get('/', [PublicController::class, 'index'])->name('welcome');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
});

// Ruta de Reservas/Contacto Pública (Protegidas con Rate Limit para evitar Spam)
Route::post('/bookings', [PublicBookingController::class, 'store'])->middleware('throttle:6,1')->name('bookings.store');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

// Rutas de Perfil (Usuario Autenticado)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Panel de Administración
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard & Bookings (Accesible para todos los roles)
    Route::middleware('role:super_admin,supervisor,reception')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    });

    // Rooms (Accesible para Super Admin y Supervisor)
    Route::middleware('role:super_admin,supervisor')->group(function () {
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);

        // Promotions (Popups) - Only for super admin and supervisor
        Route::middleware('role:super_admin,supervisor')->group(function () {
            Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
            Route::post('promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PromotionController::class, 'toggleActive'])->name('promotions.toggle');
        });

        Route::get('pages', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/generate', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    });

    // Full Access (Solo Super Admin)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
        Route::post('gallery/{gallery}/toggle-carousel', [\App\Http\Controllers\Admin\GalleryController::class, 'toggleCarousel'])->name('gallery.toggle-carousel');
        Route::resource('attractions', \App\Http\Controllers\Admin\AttractionController::class);

        Route::get('content', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
        Route::post('content', [\App\Http\Controllers\Admin\ContentController::class, 'update'])->name('content.update');

        // Rutas de Mantenimiento y Caché (Aseguradas con POST y Confirmación de Password)
        Route::middleware(['password.confirm'])->group(function () {
            Route::post('clear-cache', function () {
                try {
                    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                    \Illuminate\Support\Facades\Artisan::call('view:clear');
                    \Illuminate\Support\Facades\Artisan::call('config:clear');
                    \Illuminate\Support\Facades\Artisan::call('cache:clear');
                    return back()->with('success', '✅ [V3] LIMPIEZA PROFUNDA COMPLETADA: Caché de vistas, configuración y optimización eliminada.');
                } catch (\Exception $e) {
                    return back()->with('error', "❌ Error: " . $e->getMessage());
                }
            })->name('clear-cache');

            // Ruta de reparación maestra para SSL y Diseño (Asegurada con POST)
            Route::post('repair-ssl', function () {
                try {
                    $hotFile = public_path('hot');
                    if (file_exists($hotFile)) {
                        unlink($hotFile);
                    }
                    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                    return back()->with('success', "✅ Reparación completada. Se eliminó el archivo de conflicto y se limpió la caché. Por favor, usa una ventana de INCÓGNITO.");
                } catch (\Exception $e) {
                    return back()->with('error', "❌ Error durante la reparación: " . $e->getMessage());
                }
            })->name('repair-ssl');
        });

        // Diagnóstico restringido a entorno local
        if (app()->environment('local')) {
            Route::get('check-php', function () {
                return "<h3>Diagnóstico del Servidor</h3>" .
                    "• MEMORY LIMIT: " . ini_get('memory_limit') . "<br>" .
                    "• UPLOAD MAX SIZE: " . ini_get('upload_max_filesize') . "<br>" .
                    "• POST MAX SIZE: " . ini_get('post_max_size') . "<br>" .
                    "• MAX EXECUTION TIME: " . ini_get('max_execution_time') . "s";
            });
        }

        Route::get('sync-gallery', function () {
            try {
                $files = \Illuminate\Support\Facades\Storage::disk('public')->files('gallery');
                $added = 0;
                $maxOrder = \App\Models\Gallery::max('order') ?? 0;

                foreach ($files as $file) {
                    $publicPath = '/storage/' . $file;

                    // Solo procesar si no existe en la base de datos y es imagen
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($ext, ['webp', 'jpg', 'jpeg', 'png']) && !\App\Models\Gallery::where('image_path', $publicPath)->exists()) {
                        \App\Models\Gallery::create([
                            'image_path' => $publicPath,
                            'title_es' => basename($file),
                            'title_en' => basename($file),
                            'order' => ++$maxOrder,
                            'show_in_carousel' => 0
                        ]);
                        $added++;
                    }
                }

                if (request()->ajax() || request()->wantsJson() || request()->has('ajax')) {
                    return response()->json([
                        'success' => true,
                        'message' => "Se añadieron $added nuevas imágenes encontradas en el servidor.",
                        'added_count' => $added
                    ]);
                }

                return "✅ Sincronización completada. Se añadieron $added nuevas imágenes encontradas en el servidor. <br><br><a href='" . route('admin.gallery.index') . "'>Volver a la Galería</a>";
            } catch (\Exception $e) {
                if (request()->ajax() || request()->wantsJson() || request()->has('ajax')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ], 500);
                }
                return "❌ Error: " . $e->getMessage();
            }
        })->name('gallery.sync');


        // Gestión de Galería
        Route::post('gallery/bulk-carousel', [\App\Http\Controllers\Admin\GalleryController::class, 'bulkUpdateCarousel'])->name('gallery.bulk-carousel');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/test-email', [\App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');

        // Editor
        Route::post('editor/render', [\App\Http\Controllers\Admin\ComponentController::class, 'render'])->name('editor.render');
        Route::post('editor/upload', [\App\Http\Controllers\Admin\ComponentController::class, 'upload'])->name('editor.upload');
        Route::get('editor/preview/{page?}', [\App\Http\Controllers\Admin\PageController::class, 'preview'])->name('editor.preview');
        Route::get('editor/{page?}', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('editor.edit');
        Route::post('editor/{page}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('editor.update');

    });
});
require __DIR__ . '/auth.php';

// Rutas auxiliares para Despliegue en cPanel (Uso único)
// IMPORTANTE: Elimine o comente estas rutas luego de configurar su servidor en Producción.
Route::get('/cpanel-setup/storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Symlink creado correctamente.";
    } catch (\Exception $e) {
        return "Error creando symlink: " . $e->getMessage();
    }
});

Route::get('/cpanel-setup/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Migraciones ejecutadas exitosamente sin borrar datos.";
    } catch (\Exception $e) {
        return "Error en migración: " . $e->getMessage();
    }
});
