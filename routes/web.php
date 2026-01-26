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

// Ruta de Reservas/Contacto Pública
Route::post('/bookings', [PublicBookingController::class, 'store'])->name('bookings.store');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Rutas de Perfil (Usuario Autenticado)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel de Administración
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard & Bookings (Accesible para todos los roles)
    Route::middleware('role:super_admin,supervisor,reception')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        // Add booking processing routes if needed
    });

    // Rooms (Accesible para Super Admin y Supervisor)
    Route::middleware('role:super_admin,supervisor')->group(function () {
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);

        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/generate', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    });

    // Full Access (Solo Super Admin)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
        Route::get('gallery/{gallery}/toggle-carousel', [\App\Http\Controllers\Admin\GalleryController::class, 'toggleCarousel'])->name('gallery.toggleCarousel');
        Route::resource('attractions', \App\Http\Controllers\Admin\AttractionController::class);

        Route::get('content', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
        Route::post('content', [\App\Http\Controllers\Admin\ContentController::class, 'update'])->name('content.update');

        // Rutas de Mantenimiento y Caché
        Route::get('clear-cache', function () {
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                return "✅ [V3] LIMPIEZA PROFUNDA COMPLETADA: Caché de vistas, configuración y optimización eliminada. Por favor, realiza una nueva prueba de reserva ahora.";
            } catch (\Exception $e) {
                return "❌ Error: " . $e->getMessage();
            }
        });

        // Ruta de reparación maestra para SSL y Diseño
        Route::get('repair-ssl', function () {
            try {
                $hotFile = public_path('hot');
                $deletedHot = false;
                if (file_exists($hotFile)) {
                    unlink($hotFile);
                    $deletedHot = true;
                }

                \Illuminate\Support\Facades\Artisan::call('optimize:clear');

                $msg = "✅ Reparación completada con éxito.<br>";
                if ($deletedHot) {
                    $msg .= "• Se eliminó el archivo de conflicto 'hot'.<br>";
                }
                $msg .= "• Toda la caché del servidor ha sido limpiada.<br><br>";
                $msg .= "<b>Por favor, abre el sitio ahora en una VENTANA DE INCÓGNITO.</b>";

                return $msg;
            } catch (\Exception $e) {
                return "❌ Error durante la reparación: " . $e->getMessage();
            }
        });

        // Gestión de Galería
        Route::post('gallery/bulk-carousel', [\App\Http\Controllers\Admin\GalleryController::class, 'bulkUpdateCarousel'])->name('gallery.bulk-carousel');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/test-email', [\App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');
    });
});

require __DIR__ . '/auth.php';
