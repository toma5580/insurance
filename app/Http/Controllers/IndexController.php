<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Policy;
use Illuminate\Http\Request;

class IndexController extends Controller {

    /**
     * Create a new index controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth', array(
            'except'    => 'javascript'
        ));
        $this->middleware('localize_auth', array(
            'except'    => 'javascript'
        ));
    }

    /**
     * Get the user's dashboard.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getDashboard(Request $request) {
        $user = $request->user();
        $view_data = array();
        $view_data['company'] = $user->company;
        $view_data['company']->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($view_data['company']->currency_code)['symbol'];
        switch($user->role) {
            case 'broker':
            case 'staff':
                $view_data['latest_policies'] = $user->inviteePolicies()->orderBy('created_at', 'desc')->take(10)->get();
                break;
            case 'client':
                $view_data['latest_policies'] = $user->policies()->orderBy('created_at', 'desc')->take(10)->get();
                break;
            case 'admin':
                $view_data['latest_policies'] = $view_data['company']->policies()->orderBy('created_at', 'desc')->take(10)->get();
                break;
            case 'super':
                $view_data['latest_policies'] = Policy::orderBy('created_at', 'desc')->take(10)->get();
                break;
        }
        $view_data['latest_policies']->transform(function($policy) {
            $policy->paid = $policy->payments->sum('amount');
            $policy->due = $policy->premium - $policy->paid;
            $time_to_expiry = strtotime(date('Y-m-d')) - strtotime($policy->expiry);
            $policy->statusClass = $policy->due > 0 ? ($time_to_expiry < 1 ? 'warning' : 'negative') : 'positive';
            return $policy;
        });
        
        return view($user->role . '.dashboard', $view_data);
    }

    /**
     * Control root path redirection.
     * 
     * @return \Illuminate\Http\Response
     */
    function get() {
        $response = redirect()->action('Auth\AuthController@getAuth');
        if(auth()->check()) {
            $response = redirect()->action('IndexController@getDashboard');
        }
        return $response;
    }

    /**
     * Get Insura's dynamic javascript.
     * 
     * @return \Illuminate\Http\Response
     */
    public function javascript() {
        session()->reflash();
        return response(view('global.javascript'), 200)->header('Content-Type', 'text/javascript');
    }
}
