<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Attachment Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing user notes
    | Why don't you explore it?
    |
    */

    /**
     * Create a new note controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add a note
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $this->validate($request, array(
            'amount'    => 'numeric|required',
            'client'    => 'exists:users,id|integer|required',
            'date'      => 'date|required',
            'method'    => 'in:card,cash,paypal|required',
            'policy'    => 'exists:policies,id|integer|required'
        ));
        $client = User::find($request->client);
        $policy = $client->policies()->find($request->policy);
        $payment = new Payment($request->only(array('amount', 'date', 'method')));
        $payment->client()->associate($client);
        $payment->policy()->associate($policy);
        $payment->save();

        return redirect()->back()->with('success', trans('payments.message.success.added'));
    }
}
