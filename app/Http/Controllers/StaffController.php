<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\Company;
use App\Models\User;
use App\Pagination\SimpleSemanticUIPresenter;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class StaffController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Staff Member Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing staff Why
    | don't you explore it?
    |
    */

    /**
     * Create a new staff controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add a staff member
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $this->validate($request, [
            'address'           => 'max:256|string',
            'birthday'          => 'date',
            'commission_rate'   => 'numeric|required',
            'company_id'        => 'integer|sometimes',
            'email'             => "email|required|unique:users",
            'first_name'        => 'max:32|min:3|string|required',
            'last_name'         => 'max:32|min:3|string',
            'phone'             => 'max:16|string',
            'profile_image'     => 'image'
        ]);
        $user = $request->user();
        $company = null;
        try {
            $company = Company::findOrFail($request->company_id);
        }catch(ModelNotFoundException $e) {
            $company = $user->company;
        }
        $password = bcrypt(str_random('8'));

        $profile_image_filename = 'default-profile.jpg';
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $profile_image_filename = str_random(7). '-profile.' . str_replace('jpeg', 'jpg', $request->file('profile_image')->guessExtension());
            try{
                $request->file('profile_image')->move(storage_path('app/images/users/'), $profile_image_filename);
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('staff.message.errors.file', array(
                        'filename'  => $request->file('profile_image')->getClientOriginalName()
                    ))
                ));
            }
        }

        $staff = $company->users()->create(array(
            'address'                   => $request->address ?: null,
            'birthday'                  => $request->birthday ?: null,
            'commission_rate'           => $request->commission_rate,
            'email'                     => $request->email,
            'first_name'                => $request->first_name,
            'last_name'                 => $request->last_name ?: null,
            'locale'                    => $user->locale,
            'phone'                     => $request->phone ?: null,
            'profile_image_filename'    => $profile_image_filename
        ));
        $staff->password = 'InsuraPasswordsAreLongButNeedToBeSetByInvitedUsersSuchAsThis';
        $staff->role = 'staff';
        $staff->save();

        // Dispatch job to send welcome email
        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        DB::table(config('auth.password.table'))->insert(['email' => $staff->email, 'token' => $token, 'created_at' => new Carbon]);
        $job = new SendWelcomeEmail($token, $staff);
        $this->dispatch($job->onQueue('emails')->delay(10));

        return redirect()->back()->with('success', trans('staff.message.success.added'));
    }

    /**
     * Delete a staff member
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $staff
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, User $staff) {
        $staff->delete();
        return redirect()->action('StaffController@getAll')->with('status', trans('staff.message.info.deleted'));
    }

    /**
     * Update a staff member's profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $staff) {
        $this->validate($request, array(
            'address'           => 'max:256|string',
            'birthday'          => 'date',
            'commission_rate'   => 'numeric|required',
            'company_id'        => 'integer|sometimes',
            'email'             => "email|required|unique:users,email,{$staff->id}",
            'first_name'        => 'max:32|min:3|string|required',
            'last_name'         => 'max:32|min:3|string',
            'phone'             => 'max:16|string',
            'profile_image'     => 'image'
        ));

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $profile_image_filename = str_random(7). '-profile.' . str_replace('jpeg', 'jpg', $request->file('profile_image')->guessExtension());
            try{
                $request->file('profile_image')->move(storage_path('app/images/users/'), $profile_image_filename);
                $profile_image_storage_path = 'images/users/' . $staff->profile_image_filename;
                if($staff->profile_image_filename !== 'default-profile.jpg' && Storage::has($profile_image_storage_path)) {
                    Storage::delete($profile_image_storage_path);
                }
                $staff->profile_image_filename = $profile_image_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('staff.message.errors.file', array(
                        'filename'  => $request->file('profile_image')->getClientOriginalName()
                    ))
                ));
            }
        }

        $staff->address                 = $request->address ?: null;
        $staff->birthday                = $request->birthday ?: null;
        $staff->commission_rate         = $request->commission_rate;
        $staff->email                   = $request->email;
        $staff->first_name              = $request->first_name;
        $staff->last_name               = $request->last_name ?: null;
        $staff->phone                   = $request->phone ?: null;
        if(!is_null($request->company_id)) {
            try {
                $company = Company::findOrFail($request->company_id);
                $staff->company()->associate($company);
            }catch(ModelNotFound $e) {
                return redirect()->back()->withErrors(array(
                    trans('companies.message.error.missing')
                ))->withInput();
            }
        }
        $staff->save();

        return redirect()->back()->with('success', trans('staff.message.success.edited'));
    }

    /**
     * Get all staff members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request) {
        $user = $request->user();
        $view_data = array();
        if($user->role === 'super') {
            $view_data['companies'] = Company::all();
            $view_data['staff'] =  User::staff()->withStatus()->simplePaginate(8);
            $view_data['staff']->transform(function($employee) {
                $employee->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($employee->company->currency_code)['symbol'];
                return $employee;
            });
        }else {
            $view_data['staff'] = $user->company->staff()->withStatus()->simplePaginate(8);
            $view_data['staff']->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($user->company->currency_code)['symbol'];
        }
        $view_data['staff']->transform(function($employee) {
            $employee->sales = $employee->inviteePolicies->sum('premium');
            $employee->commission = ($employee->commission_rate / 100) * $employee->sales;
            $employee->paid = $employee->inviteePayments->sum('amount');
            $employee->due = $employee->sales - $employee->paid;
            return $employee;
        });
        $view_data['presenter'] = new SimpleSemanticUIPresenter($view_data['staff']);
        return view($user->role . '.staff.all', $view_data);
    }

    /**
     * Get one staff members
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\User  $staff
     * @return \Illuminate\Http\Response
     */
    public function getOne(Request $request, User $staff) {
        $user = $request->user();
        $view_data = array(
            'staff' => $staff
        );
        if($user->role === 'super') {
            $view_data['companies'] = Company::all();
        }
        $view_data['clients'] = $staff->invitees()->get();
        $view_data['policies'] = $staff->inviteePolicies->transform(function($policy) {
            $policy->paid = $policy->payments->sum('amount');
            $policy->due = $policy->premium - $policy->paid;
            $time_to_expiry = strtotime(date('Y-m-d')) - strtotime($policy->expiry);
            $policy->statusClass = $policy->due > 0 ? ($time_to_expiry < 1 ? 'warning' : 'negative') : 'positive';
            return $policy;
        });
        $view_data['staff']->currency_symbol = collect(config('insura.currencies.list'))->keyBy('code')->get($staff->company->currency_code)['symbol'];
        return view($user->role . '.staff.one', $view_data);
    }
}
