<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';

        if (Auth::check() && Auth::user()->language) {
            $locale = Auth::user()->language;
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        if (in_array($locale, ['en', 'ru', 'lv'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
