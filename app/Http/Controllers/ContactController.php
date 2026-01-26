<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Forzar recarga de configuración (por si cambió recientemente en la sesión)
        \Illuminate\Support\Facades\Mail::purge('smtp');

        // Obtener el correo donde el hotel desea recibir las consultas
        $recipientEmail = Setting::where('key', 'hotel_email')->value('value')
            ?? Setting::where('key', 'mail_from_address')->value('value')
            ?? config('mail.from.address');

        Log::info('Intentando enviar correo de contacto a: ' . $recipientEmail);

        try {
            Mail::to($recipientEmail)->send(new ContactMail($validated));

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Tu mensaje ha sido enviado con éxito.']);
            }

            return redirect()->back()->with('success', 'Tu mensaje ha sido enviado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error enviando correo de contacto: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Hubo un error al enviar el mensaje. Inténtalo de nuevo más tarde.'], 500);
            }

            return redirect()->back()->with('error', 'Hubo un error al enviar el mensaje.');
        }
    }
}
