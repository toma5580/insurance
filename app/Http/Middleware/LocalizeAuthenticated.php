<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class LocalizeAuthenticated {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        if(Auth::check()) {
            config(array(
                'app.locale' => ($request->user()->locale)
            ));
        }

        return $next($request);
    }
}
