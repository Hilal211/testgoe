<?php

namespace App\Http\Middleware;

use Closure;

class setLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = $request->route()->parameters();
        $locale = $params['locale'];
        \App::setLocale($locale);
        \LaravelLocalization::setLocale($locale);
        return $next($request);
    }
}
