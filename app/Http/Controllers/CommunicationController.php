<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Pagination\SemanticUIPresenter;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CommunicationController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Communication Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing emails and texts. Why
    | don't you explore it?
    |
    */

    /**
     * Create a new communication controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Get all contacts for the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        $user = $request->user();
        $page = $request->page ?: 1;
        $view_data = array();
        $view_data['contacts'] = $user->incomingEmails->map(function($email) {
            return $email->sender;
        })->merge($user->outgoingEmails->map(function($email) {
            return $email->recipient;
        }))->merge($user->incomingTexts->map(function($text) {
            return $text->sender;
        }))->merge($user->outgoingTexts->map(function($text) {
            return $text->recipient;
        }))->unique('id')->values()->sortBy('first_name');
        $view_data['contacts'] = new LengthAwarePaginator($view_data['contacts']->slice(($page - 1) * 15, 15), $view_data['contacts']->count(), 15, $page);
        $view_data['presenter'] = new SemanticUIPresenter($view_data['contacts']);
        if($user->role === 'super') {
            $view_data['admins'] = Company::all()->map(function($company) {
                return $company->admin;
            });
        }
        
        return view($user->role . '.communication', $view_data);
    }
}
