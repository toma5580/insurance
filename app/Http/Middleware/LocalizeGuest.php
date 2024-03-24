<?php

namespace App\Http\Middleware;

use Closure;

class LocalizeGuest {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $languages = collect(config('insura.languages'));
        if(isset($request->language) && $languages->contains('locale', $request->language)) {
            config(array(
                'app.locale' => $request->language
            ));
            $language = $languages->keyBy('locale')->get($request->language);
            setlocale(LC_ALL, $language['identifiers']);
            return redirect()->back()->with('locale', config('app.locale'))->with('status', trans('middleware.message.info.language.set', array(
                'language'  => $language['name']
            )));
        }else if($request->session()->has('locale') && $languages->contains('locale', $request->session()->get('locale'))) {
            config(array(
                'app.locale' => $request->session()->get('locale')
            ));
            $request->session()->keep(array('locale'));
            setlocale(LC_ALL, $languages->keyBy('locale')->get($request->session()->get('locale'))['identifiers']);
        }

        return $next($request);
    }
}
