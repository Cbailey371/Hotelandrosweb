<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\TestMail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Excluimos campos de archivo y el token
        $data = $request->except(['_token', 'hotel_logo', 'hotel_favicon']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Manejo de Logo
        if ($request->hasFile('hotel_logo')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('hotel_logo'), 'branding', 'hotel-logo');
            Setting::updateOrCreate(['key' => 'hotel_logo'], ['value' => $path]);
        }

        // Manejo de Favicon
        if ($request->hasFile('hotel_favicon')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('hotel_favicon'), 'branding', 'hotel-favicon', 90, 64);
            Setting::updateOrCreate(['key' => 'hotel_favicon'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Configuraciones actualizadas y optimizadas con éxito.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            // Obtenemos los settings actuales (priorizando los del request si vienen, para probar sin guardar)
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            // Si el usuario envió campos de SMTP en el request, los usamos para la prueba
            $host = $request->mail_host ?? $settings['mail_host'] ?? config('mail.mailers.smtp.host');
            $port = $request->mail_port ?? $settings['mail_port'] ?? config('mail.mailers.smtp.port');
            $username = $request->mail_username ?? $settings['mail_username'] ?? config('mail.mailers.smtp.username');
            $password = $request->mail_password ?? $settings['mail_password'] ?? config('mail.mailers.smtp.password');
            $encryption = $request->mail_encryption ?? $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption');
            $fromAddress = $request->mail_from_address ?? $settings['mail_from_address'] ?? config('mail.from.address');
            $fromName = $request->mail_from_name ?? $settings['mail_from_name'] ?? config('mail.from.name');

            // Configuración dinámica
            $encryptionValue = ($encryption === 'null' || !$encryption) ? null : $encryption;

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            Config::set('mail.mailers.smtp.encryption', $encryptionValue);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            // Forzar la recreación del mailer con la nueva configuración
            Mail::purge('smtp');

            Mail::mailer('smtp')->to($request->test_email)->send(new TestMail(['test' => true]));

            return response()->json(['success' => true, 'message' => 'Correo de prueba enviado correctamente a ' . $request->test_email]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al enviar: ' . $e->getMessage()], 500);
        }
    }
}
