<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class LocaleMiddleware
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
        $locale = session()->get('locale');
        if($locale) {
            app()->setLocale($locale);
        } else {
            session()->put('locale', 'id');
            app()->setLocale('id');
        }
        return $next($request);
    }
}
