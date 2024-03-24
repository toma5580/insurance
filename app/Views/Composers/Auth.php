<?php

namespace App\Views\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Auth {
    
    /**
     * Create a new user controller instance.
     * 
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Create a new auth composer.
     *
     * @param  Request $request
     * @return void
     */
    public function __construct(Request $request) {
        // Dependencies automatically resolved by service container...
        $this->session = $request->session();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        $state = $this->session->get('state', 'login');
        $view->with('state', $state);
    }
}