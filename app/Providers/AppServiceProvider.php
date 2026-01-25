<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzamos HTTPS para evitar Mixed Content
        \Illuminate\Support\Facades\URL::forceScheme('https');

        // Forzamos el estado de HTTPS en el servidor
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }

        Schema::defaultStringLength(191);

        // Aplicar configuración SMTP y compartir settings con todas las vistas
        if (Schema::hasTable('settings')) {
            $settings = Setting::all()->pluck('value', 'key');
            view()->share('settings', $settings);

            if (isset($settings['mail_host']) && !empty($settings['mail_host'])) {
                $enc = $settings['mail_encryption'] ?? 'tls';
                $encryptionValue = ($enc === 'null' || !$enc) ? null : $enc;

                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.transport' => 'smtp',
                    'mail.mailers.smtp.host' => $settings['mail_host'],
                    'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
                    'mail.mailers.smtp.username' => $settings['mail_username'] ?? '',
                    'mail.mailers.smtp.password' => $settings['mail_password'] ?? '',
                    'mail.mailers.smtp.encryption' => $encryptionValue,
                    'mail.from.address' => $settings['mail_from_address'] ?? 'no-reply@hotel.com',
                    'mail.from.name' => $settings['mail_from_name'] ?? ($settings['hotel_name'] ?? 'Hotel Andros'),
                ]);

                // Forzamos a Laravel a olvidar cualquier instancia previa del mailer para que use la nueva config
                \Illuminate\Support\Facades\Mail::purge('smtp');
                if (config('mail.default') === 'smtp') {
                    \Illuminate\Support\Facades\Mail::purge();
                }
            }
        }
    }
}
