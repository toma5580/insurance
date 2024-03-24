<?php

namespace App\Views\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class All {
    
    /**
     * Create a new user controller instance.
     * 
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new auth composer.
     *
     * @param  Request $request
     * @return void
     */
    public function __construct(Request $request) {
        // Dependencies automatically resolved by service container...
        $this->user = $request->user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        if(!is_null($this->user)) {
            $view->with('user', $this->user);
        }
    }
}