<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\User;
use App\Pagination\SimpleSemanticUIPresenter;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Storage;

class ClientController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Client Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing clients Why
    | don't you explore it?
    |
    */

    /**
     * Create a new client controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add a client
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $this->validate($request, [
            'address'       => 'max:256|string',
            'birthday'      => 'date',
            'company_id'    => 'exists:companies,id|integer|sometimes',
            'custom_fields' => 'array|sometimes',
            'email'         => "email|required|unique:users",
            'first_name'    => 'max:32|min:3|string|required',
            'inviter_id'    => 'exists:users,id|integer|sometimes',
            'last_name'     => 'max:32|min:3|string',
            'phone'         => 'max:16|string',
            'profile_image' => 'image'
        ]);
        $user = $request->user();
        if(isset($request->company_id)) {
            $company = Company::find($request->company_id);
        }else {
            $company = $user->company;
        }
        if(isset($request->inviter_id)) {
            $inviter = User::find($request->inviter_id);
        }else {
            $inviter = $user;
        }
        $profile_image_filename = 'default-profile.jpg';
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $filename = str_random(7). '-profile.' . str_replace('jpeg', 'jpg', $request->file('profile_image')->guessExtension());
            try{
                $request->file('profile_image')->move(storage_path('app/images/users/'), $filename);
                $profile_image_filename = $filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors([
                    trans('clients.message.errors.file') . $request->file('profile_image')->getClientOriginalName()
                ])->withInput();
            }
        }

        $client = $company->users()->create(array(
            'address'                   => $request->address ?: null,
            'birthday'                  => $request->birthday ?: null,
            'email'                     => $request->email,
            'first_name'                => $request->first_name,
            'last_name'                 => $request->last_name ?: null,
            'locale'                    => $user->locale,
            'phone'                     => $request->phone ?: null,
            'profile_image_filename'    => $profile_image_filename
        ));
        $client->password = 'InsuraPasswordsAreLongButNeedToBeSetByInvitedUsersSuchAsThis';
        $client->role = 'client';
        $client->inviter()->associate($inviter);
        $client->save();

        // Save custom_fields
        if(isset($request->custom_fields)) {
            foreach($request->custom_fields as $custom_field) {
                $custom_field = new CustomField(array(
                    'label' => $custom_field['label'],
                    'type'  => $custom_field['type'],
                    'uuid'  => $custom_field['uuid'],
                    'value' => isset($custom_field['value']) ? (is_array($custom_field['value']) ? json_encode($custom_field['value']) : $custom_field['value']) : null
                ));
                $custom_field->model()->associate($client);
                $custom_field->save();
            }
        }

        // Dispatch job to send welcome email
        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        DB::table(config('auth.password.table'))->insert(['email' => $client->email, 'token' => $token, 'created_at' => new Carbon]);
        $job = new SendWelcomeEmail($token, $client);
        $this->dispatch($job->onQueue('emails')->delay(10));

        return redirect()->back()->with('success', trans('clients.message.success.added'));
    }

    /**
     * Delete a client
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, User $client) {
        $client->delete();
        return redirect()->action('ClientController@getAll')->with('status', trans('clients.message.info.deleted'));
    }

    /**
     * Update a client's profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $client) {
        $this->validate($request, array(
            'address'       => 'max:256|string',
            'birthday'      => 'date',
            'company_id'    => 'exists:companies,id|integer|sometimes',
            'custom_fields' => 'array|sometimes',
            'email'         => "email|required|unique:users,email,{$client->id}",
            'first_name'    => 'max:32|min:3|string|required',
            'inviter'       => 'exists:users,id|integer|sometimes',
            'last_name'     => 'max:32|min:3|string',
            'phone'         => 'max:16|string',
            'profile_image' => 'image'
        ));

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $profile_image_filename = str_random(7). '-profile.' . str_replace('jpeg', 'jpg', $request->file('profile_image')->guessExtension());
            try{
                $request->file('profile_image')->move(storage_path('app/images/users/'), $profile_image_filename);
                $profile_image_storage_path = 'images/users/' . $client->profile_image_filename;
                if($client->profile_image_filename !== 'default-profile.jpg' && Storage::has($profile_image_storage_path)) {
                    Storage::delete($profile_image_storage_path);
                }
                $client->profile_image_filename = $profile_image_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors([
                    trans('clients.message.errors.file') . $request->file('profile_image')->getClientOriginalName()
                ]);
            }
        }

        $client->address                 = $request->address ?: null;
        $client->birthday                = $request->birthday ?: null;
        $client->email                   = $request->email;
        $client->first_name              = $request->first_name;
        $client->last_name               = $request->last_name ?: null;
        $client->phone                   = $request->phone ?: null;
        if(!is_null($request->company_id) && $request->company_id != $client->company->id) {
            // Delete current custom fields
            $client->customFields->each(function($custom_field) {
                $custom_field->delete();
            });
            // Save new custom fields
            if(isset($request->custom_fields)) {
                foreach($request->custom_fields as $custom_field) {
                    $custom_field = new CustomField(array(
                        'label' => $custom_field['label'],
                        'type'  => $custom_field['type'],
                        'uuid'  => $custom_field['uuid'],
                        'value' => isset($custom_field['value']) ? (is_array($custom_field['value']) ? json_encode($custom_field['value']) : $custom_field['value']) : null
                    ));
                    $custom_field->model()->associate($client);
                    $custom_field->save();
                }
            }
            // Associate new company
            $client->company()->associate(Company::findOrFail($request->company_id));
        }else if(!is_null($request->company_id) && $request->company_id == $client->company->id) {
            // Save custom_fields
            if(isset($request->custom_fields)) {
                foreach($request->custom_fields as $custom_field) {
                    $client->customFields()->where('uuid', $custom_field['uuid'])->update(array(
                        'value' => isset($custom_field['value']) ? (is_array($custom_field['value']) ? json_encode($custom_field['value']) : $custom_field['value']) : null
                    ));
                }
            }
        }
        // Associate new inviter
        if(!is_null($request->inviter) && $request->inviter != $client->inviter->id) {
            $client->inviter()->associate(User::findOrFail($request->inviter));
        }
        $client->save();

        return redirect()->back()->with('success', trans('clients.message.success.edited'));
    }

    /**
     * Get all clients
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request) {
        $currencies_by_code = collect(config('insura.currencies.list'))->keyBy('code');
        $user = $request->user();
        $view_data = array();
        switch($user->role) {
            case 'super':
                $view_data['companies'] = Company::all()->map(function($company) {
                    $company->custom_fields_metadata = collect(json_decode($company->custom_fields_metadata ?: '[]'));
                    return $company;
                });
                $view_data['clients'] = User::client()->withStatus()->simplePaginate(8);
                $view_data['clients']->transform(function($client) use($currencies_by_code) {
                    $client->currency_symbol = $currencies_by_code->get($client->company->currency_code)['symbol'];
                    return $client;
                });
                break;
            case 'admin':
            case 'staff':
                $view_data['clients'] = $user->company->clients()->withStatus()->simplePaginate(8);
                $view_data['clients']->currency_symbol = $currencies_by_code->get($user->company->currency_code)['symbol'];
                break;
            case 'broker':
                $view_data['clients'] = $user->invitees()->withStatus()->simplePaginate(8);
                $view_data['clients']->currency_symbol = $currencies_by_code->get($user->company->currency_code)['symbol'];
                break;
        }
        $view_data['clients']->transform(function($client) {
            $client->premiums = $client->policies->sum('premium');
            $client->paid = $client->payments->sum('amount');
            $client->due = $client->premiums - $client->paid;
            return $client;
        });
        $view_data['presenter'] = new SimpleSemanticUIPresenter($view_data['clients']);
        
        return view($user->role . '.clients.all', $view_data);
    }

    /**
     * Get one client
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\User  $client
     * @return \Illuminate\Http\Response
     */
    public function getOne(Request $request, User $client) {
        $user = $request->user();
        $client->company->custom_fields_metadata = collect(json_decode($client->company->custom_fields_metadata ?: '[]'));
        $client->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($client->company->currency_code)['symbol'];
        $client->customFields->transform(function($custom_field) use($client) {
            $custom_field_metadata = $client->company->custom_fields_metadata->where('uuid', $custom_field->uuid)->first();
            if(isset($custom_field_metadata->required)) {
                $custom_field->required = true;
            }
            return $custom_field;
        });
        $view_data = array(
            'client'    => $client,
            'policies'  => $client->policies->map(function($policy) {
                $policy->paid = $policy->payments->sum('amount');
                $policy->due = $policy->premium - $policy->paid;
                $time_to_expiry = strtotime(date('Y-m-d')) - strtotime($policy->expiry);
                $policy->statusClass = $policy->due > 0 ? ($time_to_expiry < 1 ? 'warning' : 'negative') : 'positive';
                return $policy;
            })
        );
        if($user->role === 'super') {
            $view_data['companies'] = Company::all()->map(function($company) {
                $company->custom_fields_metadata = collect(json_decode($company->custom_fields_metadata ?: '[]'));
                return $company;
            });
        }
        
        return view($user->role . '.clients.one', $view_data);
    }
}
