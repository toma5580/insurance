<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Artisan;
use Cache;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SetupController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Setting Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing settings. Why
    | don't you explore it?
    |
    */

    /**
     * All currency codes
     *
     * @var string
     */
    protected $currencyCodes;

    /**
     * All system supported languages
     *
     * @var string
     */
    protected $languages;

    /**
     * Create a new settings controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('localize_guest');
        $this->currencyCodes = collect(config('insura.currencies.list'))->map(function($currency) {
            return $currency['code'];
        })->implode(',');
        $this->languages = collect(config('insura.languages'))->map(function($language) {
            return $language['locale'];
        })->implode(',');
    }
    
    /**
     * Cache the app for speed
     * 
     * @return \Illuminate\Http\Response
     */
    public function cache() {
        Artisan::call('config:cache');
        Artisan::call('route:cache');

        return redirect()->action('SetupController@install');
    }
    
    /**
     * Configure the system
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function configure(Request $request) {
        $this->validate($request, array(
            'account_address'           => 'max:256|string',
            'account_email'             => 'email|required',
            'aft_api_key'               => 'max:64|required_if:text_provider,aft|string',
            'aft_username'              => 'max:64|required_if:text_provider,aft|string',
            'app_locale_default'        => 'max:5|min:5|required',
            'app_url'                   => 'required|url',
            'birthday'                  => 'date',
            'company_address'           => 'max:256|string',
            'company_email'             => 'email',
            'company_name'              => 'max:64|min:3|required|string',
            'currency_code'             => 'in:' . $this->currencyCodes . '|string|required',
            'db_connection'             => 'in:mysql,pgsql,sqlite,sqlsrv|required',
            'db_database'               => 'required|string',
            'db_host'                   => 'sometimes|string',
            'db_password'               => 'confirmed|sometimes',
            'db_username'               => 'sometimes|string',
            'email_signature'           => 'string',
            'favicon'                   => 'image',
            'insura_currency_default'   => 'in:' . $this->currencyCodes . '|required',
            'insura_name'               => 'max:64|min:3|required',
            'first_name'                => 'max:32|min:3|string|required',
            'last_name'                 => 'max:32|min:3|string',
            'locale'                    => 'in:' . $this->languages . '|required',
            'logo'                      => 'image',
            'mail_driver'               => 'in:mailgun,mandrill,sendmail,ses,smtp',
            'mail_encryption'           => 'in:none,ssl,tls',
            'mail_username'             => 'max:64|min:4|string',
            'mailgun_domain'            => 'required_if:mail_driver,mailgun|string',
            'mailgun_secret'            => 'required_if:mail_driver,mailgun|string',
            'mandrill_secret'           => 'required_if:mail_driver,mandrill|string',
            'password'                  => 'confirmed|max:32||min:6|required',
            'phone'                     => 'max:16|string',
            'profile_image'             => 'image',
            'ses_key'                   => 'required_if:mail_driver,ses|string',
            'ses_region'                => 'required_if:mail_driver,ses|string',
            'ses_secret'                => 'required_if:mail_driver,ses|string',
            'smtp_host'                 => 'max:64|min:3|required_if:mail_driver,smtp',
            'smtp_password'             => 'confirmed|required_if:mail_driver,smtp',
            'smtp_port'                 => 'integer|required_if:mail_driver,smtp',
            'text_provider'             => 'in:aft,twilio|string',
            'text_signature'            => 'max:32|string',
            'twilio_auth_token'         => 'max:64|required_if:text_provider,twilio|string',
            'twilio_number'             => 'max:32|required_if:text_provider,twilio|string',
            'twilio_sid'                => 'max:64|required_if:text_provider,twilio|string'
        ));

        $config = $request->only(array(
            'app_locale_default',
            'app_url',
            'db_connection',
            'db_database',
            'db_host',
            'db_password',
            'db_username',
            'insura_currency_default',
            'insura_name',
            'mail_driver',
            'mail_encryption',
            'mail_username',
            'mailgun_domain',
            'mailgun_secret',
            'mandrill_secret',
            'ses_key',
            'ses_region',
            'ses_secret',
            'smtp_host',
            'smtp_password',
            'smtp_port'
        ));
        if($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            $insura_favicon_filename = 'favicon.' . $request->file('favicon')->guessExtension();
            try{
                $request->file('favicon')->move(storage_path('app/images/'), $insura_favicon_filename);
                if($insura_favicon_filename !== config('insura.favicon')) {
                    Storage::delete('images/' . config('insura.favicon'));
                }
                $config['insura_favicon'] = $insura_favicon_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('setup.message.error.file', array(
                        'filename'  => $request->file('favicon')->getClientOriginalName(),
                        'type'      => trans('setup.message.error.files.favicon')
                    ))
                ));
            }
        }
        if($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $insura_logo_filename = 'logo.' .$request->file('logo')->guessExtension();
            try{
                $request->file('logo')->move(storage_path('app/images/'), $insura_logo_filename);
                if($insura_logo_filename !== config('insura.logo')) {
                    Storage::delete('images/' . config('insura.logo'));
                }
                $config['insura_logo'] = $insura_logo_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('setup.message.error.file', array(
                        'filename'  => $request->file('logo')->getClientOriginalName(),
                        'type'      => trans('setup.message.error.files.logo')
                    ))
                ));
            }
        }
        $config['app_env'] = 'production';
        $env = view('templates.env', ['env' => $config]);
        Storage::disk('base')->put('.env', $env);
        Artisan::call('key:generate');

        $seed_data = $request->only(array(
            'account_address',
            'account_email',
            'aft_api_key',
            'aft_username',
            'birthday',
            'company_address',
            'company_email',
            'company_name',
            'currency_code',
            'email_signature',
            'first_name',
            'last_name',
            'locale',
            'password',
            'phone',
            'text_provider',
            'text_signature',
            'twilio_auth_token',
            'twilio_number',
            'twilio_sid'
        ));
        $seed_data['profile_image_filename'] = 'default-profile.jpg';
        if($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $profile_image_filename = str_random(7). '-profile.' . str_replace('jpeg', 'jpg', $request->file('profile_image')->guessExtension());
            try{
                $request->file('profile_image')->move(storage_path('app/images/users/'), $profile_image_filename);
                $seed_data['profile_image_filename'] = $profile_image_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('setup.message.error.file', array(
                        'filename'  => $request->file('profile_image')->getClientOriginalName(),
                        'type'      => trans('setup.message.error.files.profile_image')
                    ))
                ));
            }
        }
        Cache::put('seed_data', $seed_data, 1);

        return redirect()->action('SetupController@cache');
    }
    
    /**
     * Get the configuration page
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        session()->reflash();
        return view('global.setup', array(
            'alternate_url' => implode('/', array_slice(explode('/', $request->url()), 0, 3))
        ));
    }
    
    /**
     * Install and activate the system admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function install() {
        config(array(
            'app.debug' => true
        ));
        $data = Cache::pull('seed_data');
        Artisan::call('migrate', array(
            '--force'   => true
        ));
        $company = Company::create(array(
            'address'           => $data['company_address'] ?: null,
            'aft_api_key'       => $data['aft_api_key'] ?: null,
            'aft_username'      => $data['aft_username'] ?: null,
            'currency_code'     => $data['currency_code'],
            'email'             => $data['company_email'] ?: null,
            'email_signature'   => $data['email_signature'] ?: null,
            'name'              => $data['company_name'],
            'text_provider'     => $data['text_provider'] ?: null,
            'text_signature'    => $data['text_signature'] ?: null,
            'twilio_auth_token' => $data['twilio_auth_token'] ?: null,
            'twilio_number'     => $data['twilio_number'] ?: null,
            'twilio_sid'        => $data['twilio_sid'] ?: null
        ));
        $admin = $company->admin()->create(array(
            'address'                   => $data['account_address'] ?: null,
            'birthday'                  => $data['birthday'] ?: null,
            'email'                     => $data['account_email'],
            'first_name'                => $data['first_name'],
            'last_name'                 => $data['last_name'] ?: null,
            'locale'                    => $data['locale'],
            'phone'                     => $data['phone'] ?: null,
            'profile_image_filename'    => $data['profile_image_filename']
        ));
        $admin->role = 'super';
        $admin->password = bcrypt($data['password']);
        $admin->save();

        return redirect()->action('Auth\AuthController@getAuth')->with('success', trans('setup.message.success.setup'))->withInput(array(
            'email' => $data['account_email']
        ));
    }
}
