<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('locale') ?? $request->query('locale') ?? session('locale');

        if ($locale && in_array($locale, ['en', 'es'])) {
            \Illuminate\Support\Facades\Log::info('SetLocale: Setting locale to ' . $locale . ' (Source: ' . ($request->input('locale') ? 'Input' : ($request->query('locale') ? 'Query' : 'Session')) . ')');
            app()->setLocale($locale);

            // Persist to session if it came from request
            if (!session()->has('locale') || session('locale') !== $locale) {
                session()->put('locale', $locale);
            }
        } else {
            \Illuminate\Support\Facades\Log::info('SetLocale: No valid locale found. Defaulting to ' . app()->getLocale());
        }

        return $next($request);
    }
}
