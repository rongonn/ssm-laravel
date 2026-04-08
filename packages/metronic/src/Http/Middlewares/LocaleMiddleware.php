<?php

namespace Isotope\Metronic\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            app()->setLocale(Auth::user()->locale ? Auth::user()->locale : 'en');
        }
        else{
            app()->setLocale(session('locale', app()->getLocale()));
        }
        return $next($request);
    }
}