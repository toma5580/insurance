<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Policy;
use App\Models\Product;
use App\Models\User;
use App\Pagination\SemanticUIPresenter;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PolicyController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Policy Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing policies
    | Why don't you explore it?
    |
    */

    /**
     * Create a new policy controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add a policy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $validation_rules = array(
            'amount'            => 'array|required_with_all:date,method',
            'amount.*'          => 'numeric',
            'beneficiaries'     => 'max:512|string',
            'custom_fields'     => 'array|sometimes',
            'date'              => 'array|required_with_all:amount,method',
            'date.*'            => 'date',
            'expiry'            => 'date|required',
            'method'            => 'array|required_with_all:amount,date',
            'method.*'          => 'in:card,cash,paypal',
            'owners'            => 'array|required',
            'owners.*'          => 'exists:users,id|integer',
            'payer'             => 'max:32|required|string',
            'premium'           => 'array|required',
            'premium.*'         => 'numeric',
            'product'           => 'exists:products,id|integer|required',
            'renewal'           => 'date|required',
            'special_remarks'   => 'max:2048|string',
            'type'              => 'in:annually,monthly,weekly|required|string'
        );
        foreach($request->owners as $owner) {
            $validation_rules["premium.{$owner}"] = 'required';
            $validation_rules["amount.{$owner}"] = "required_with_all:date.{$owner},method.{$owner}";
            $validation_rules["date.{$owner}"] = "required_with_all:amount.{$owner},method.{$owner}";
            $validation_rules["method.{$owner}"] = "required_with_all:amount.{$owner},date.{$owner}";
        }
        $this->validate($request, $validation_rules);
        $company = $request->user()->company;
        $product = $company->products()->find($request->product);
        
        foreach($request->owners as $owner) {
            $client = $company->clients()->find($owner);
            $policy = new Policy($request->only(array('beneficiaries', 'expiry', 'payer', 'renewal', 'type')));
            $policy->premium = $request->premium[$owner];
            $policy->special_remarks = str_replace(["\n", "\r\n"], '<br/>', $request->special_remarks);
            $policy->ref_no = strtoupper(str_random(8));
            $policy->client()->associate($client);
            $policy->product()->associate($product);
            $policy->save();

            // Save custom_fields
            if(isset($request->custom_fields)) {
                foreach($request->custom_fields as $custom_field) {
                    $custom_field = new CustomField(array(
                        'label' => $custom_field['label'],
                        'type'  => $custom_field['type'],
                        'uuid'  => $custom_field['uuid'],
                        'value' => isset($custom_field['value']) ? (is_array($custom_field['value']) ? json_encode($custom_field['value']) : $custom_field['value']) : null
                    ));
                    $custom_field->model()->associate($policy);
                    $custom_field->save();
                }
            }

            if(isset($request->amount[$owner]) && !empty($request->amount[$owner])) {
                $payment = new Payment(array(
                    'amount'    => $request->amount[$owner],
                    'date'      => $request->date[$owner],
                    'method'    => $request->method[$owner]
                ));
                $payment->client()->associate($client);
                $payment->policy()->associate($policy);
                $payment->save();
            }
        }

        return redirect()->back()->with('success', trans('policies.message.success.added', array(
            'count' => count($request->owners)
        )));
    }

    /**
     * Delete a policy
     *
     * @param  \Illuminate\Http\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function delete(Policy $policy) {
        $policy->delete();
        return redirect()->action('PolicyController@getAll')->with('status', trans('policies.message.info.deleted'));
    }

    /**
     * Update a policy
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Policy $policy) {
        $this->validate($request, array(
            'beneficiaries'     => 'max:512|string',
            'custom_fields'     => 'array|sometimes',
            'expiry'            => 'date|required',
            'payer'             => 'max:32|required|string',
            'premium'           => 'numeric|required',
            'product'           => 'exists:products,id|integer|required',
            'renewal'           => 'date|required',
            'special_remarks'   => 'max:2048|string',
            'type'              => 'in:annually,monthly,weekly|required|string'
        ));
        $company = $policy->client->company;

        $policy->beneficiaries      = $request->beneficiaries ?: null;
        $policy->expiry             = $request->expiry;
        $policy->payer              = $request->payer;
        $policy->premium            = $request->premium;
        $policy->renewal            = $request->renewal;
        $policy->special_remarks    = str_replace(["\n", "\r\n"], '<br/>', $request->special_remarks) ?: null;
        $policy->type               = $request->type;
        if($request->product != $policy->product->id) {
            $product = $company->products()->find($request->product);
            $policy->product()->associate($product);
        }
        $policy->save();

        // Save custom_fields
        if(isset($request->custom_fields)) {
            foreach($request->custom_fields as $custom_field) {
                $policy->customFields()->where('uuid', $custom_field['uuid'])->update(array(
                    'value' => isset($custom_field['value']) ? (is_array($custom_field['value']) ? json_encode($custom_field['value']) : $custom_field['value']) : null
                ));
            }
        }

        return redirect()->back()->with('success', trans('policies.message.success.edited'));
    }

    /**
     * Get all policies
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request) {
        $currencies_by_code = collect(config('insura.currencies.list'))->keyBy('code');
        $filters = $this->getFilters($request);
        $user = $request->user();
        $view_data = array();
        switch($user->role) {
            case 'super':
            case 'admin':
            case 'staff':
                $view_data['policies'] = $user->company->policies()->insuraFilter($filters)->paginate(15);
                $view_data['clients'] = $user->company->clients()->get();
                break;
            case 'broker':
                $view_data['policies'] = $user->inviteePolicies()->insuraFilter($filters)->paginate(15);
                $view_data['clients'] = $user->invitees()->get();
                break;
            case 'client':
                $view_data['policies'] = $user->policies()->insuraFilter($filters)->paginate(15);
                break;
        }
        $view_data['policies']->currency_symbol = $currencies_by_code->get($user->company->currency_code)['symbol'];
        $view_data['policies']->transform(function($policy) {
            $policy->paid = $policy->payments->sum('amount');
            $policy->due = $policy->premium - $policy->paid;
            $time_to_expiry = strtotime(date('Y-m-d')) - strtotime($policy->expiry);
            $policy->statusClass = $policy->due > 0 ? ($time_to_expiry < 1 ? 'warning' : 'negative') : 'positive';
            return $policy;
        });
        $view_data['policies']->lastOnPreviousPage = ($view_data['policies']->currentPage() - 1) * $view_data['policies']->perPage();
        $view_data['filter'] = count($filters) > 0;
        if($view_data['filter']) {
            $view_data['filters'] = $filters;
        }
        $view_data['presenter'] = new SemanticUIPresenter($view_data['policies']);

        return view($user->role . '.policies.all', $view_data);
    }

    /**
     * get valid filters from a request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function getFilters(Request $request) {
        $master = array('due_max','due_min','expiry_from','expiry_to','policy_ref','premium_max','premium_min','product','renewal_from','renewal_to');
        $filters = collect($request->only($master))->reject(function($val) {
            return is_null($val) || empty($val);
        })->toArray();
        return $filters;
    }

    /**
     * Get one policy
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function getOne(Request $request, Policy $policy) {
        $user = $request->user();
        $policy->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($policy->client->company->currency_code)['symbol'];
        $policy->paid = $policy->payments->sum('amount');
        $policy->due = $policy->premium - $policy->paid;
        $policy->active = (time() - strtotime($policy->expiry)) < 0;
        $policy->statusClass = $policy->due > 0 ? ($policy->active ? 'orange' : 'red') : 'green';
        $policy->customFields->transform(function($custom_field) use ($policy) {
            $custom_field_metadata = collect($policy->product->company->custom_fields_metadata)->where('uuid', $custom_field->uuid)->first();
            if(isset($custom_field_metadata->required)) {
                $custom_field->required = true;
            }
            if($custom_field->type === 'select') {
                $custom_field->value = json_decode($custom_field->value);
            }
            return $custom_field;
        });
        $view_data = array(
            'policy' => $policy
        );
        if($user->role === 'super') {
            $view_data['companies'] = Product::all();
        }
        
        return view($user->role . '.policies.one', $view_data);
    }
}
