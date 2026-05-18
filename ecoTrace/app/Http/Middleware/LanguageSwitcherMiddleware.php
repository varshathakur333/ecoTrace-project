<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageSwitcherMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->get('lang') ?? session('locale') ?? $request->cookie('locale') ?? config('app.locale');
        
        if (in_array($locale, ['en', 'hi'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
