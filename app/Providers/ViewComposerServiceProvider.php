<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot() {
        // Index view should always have $state defined
        view()->composer('*', 'App\Views\Composers\All');
        view()->composer('global.auth', 'App\Views\Composers\Auth');
        view()->composer(array(
            'global.header',
            'templates.sidebar'
        ), 'App\Views\Composers\Navigation');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        //
    }
}