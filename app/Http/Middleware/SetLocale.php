<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Parbauda lietotāja lokali un piešķir to esošam HTTP vaicājumam.
     * Ja sessijā lokales vēl nav, tiek piešķirta defolta vertība.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedLocales = ['lv', 'en'];

        // Ja lietotājs ir administrators, kurjers vai operators viņa izvēlne tiek attēlota tika latviešu valodā

        if ($request->user() !== null && in_array($request->user()->role, ['admin', 'operator', 'courier'])) {
            $locale = 'lv';
        } else {
            $locale = session('locale', config('app.locale'));
        }

        // Aizsardība no neeksistējošam lokalēm, piemēram, ievadītām adresu joslā
        if (!in_array($locale, $allowedLocales, true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);  

        return $next($request);
    }
}
