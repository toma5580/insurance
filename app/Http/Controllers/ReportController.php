<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }
    
    public function get(Request $request) {
        $user = $request->user();
        $view_data = array();
        $view_data['company'] = $user->company;
        $view_data['company']->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($view_data['company']->currency_code)['symbol'];
        $view_data['year'] = $request->year ?: date('Y');
        
        return view($user->role . '.reports', $view_data);
    }
}
