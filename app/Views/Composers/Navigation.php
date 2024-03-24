<?php

namespace App\Views\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class Navigation {
    
    /**
     * Create a new user controller instance.
     * 
     * @var Illuminate\Support\Collection
     */
    protected $unread_chats;

    /**
     * Create a new auth composer.
     *
     * @param  Request $request
     * @return void
     */
    public function __construct(Request $request) {
        // Dependencies automatically resolved by service container...
        $this->unread_chats = $request->user()->incomingChats()->unread();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        $view->with('unread_chats_count', $this->unread_chats->count());
    }
}