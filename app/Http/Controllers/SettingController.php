<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SettingController extends Controller {
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
     * Create a new settings controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }
    
    /**
     * Re-cache the app's config
     * 
     * @return \Illuminate\Http\Response
     */
    public function cache() {
        Artisan::call('config:cache');
        // Give adequate time for the config cache to be output before continuing
        sleep(5);

        return redirect()->action('SettingController@load');
    }
    
    /**
     * Edit system settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        $request->session()->flash('tab', 'system');
        $this->validate($request, array(
            'app_locale_default'        => 'max:5|min:5|required',
            'favicon'                   => 'image',
            'insura_currency_default'   => 'in:' . collect(config('insura.currencies.list'))->map(function($currency) {
                return $currency['code'];
            })->implode(',') . '|required',
            'insura_name'               => 'max:64|min:3|required',
            'logo'                      => 'image',
            'mail_driver'               => 'in:mailgun,mandrill,sendmail,ses,smtp',
            'mail_encryption'           => 'in:none,ssl,tls',
            'mail_username'             => 'max:64|min:4|string',
            'mailgun_domain'            => 'string',
            'mailgun_secret'            => 'string',
            'mandrill_secret'           => 'string',
            'ses_key'                   => 'string',
            'ses_region'                => 'string',
            'ses_secret'                => 'string',
            'smtp_host'                 => 'max:64|min:3',
            'smtp_password'             => 'confirmed',
            'smtp_port'                 => 'integer'
        ));

        $input = $request->only(array(
            'app_locale_default',
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

        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            $insura_favicon_filename = 'favicon.' . $request->file('favicon')->guessExtension();
            try{
                $request->file('favicon')->move(storage_path('app/images/'), $insura_favicon_filename);
                $insura_favicon_storage_path = 'images/' . config('insura.favicon');
                if($insura_favicon_filename !== config('insura.favicon') && Storage::has($insura_favicon_storage_path)) {
                    Storage::delete($insura_favicon_storage_path);
                }
                $input['insura_favicon'] = $insura_favicon_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('settings.message.error.file', array(
                        'filename'  => $request->file('favicon')->getClientOriginalName(),
                        'type'      => trans('settings.message.error.files.favicon')
                    ))
                ));
            }
        }
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $insura_logo_filename = 'logo.' .$request->file('logo')->guessExtension();
            try{
                $request->file('logo')->move(storage_path('app/images/'), $insura_logo_filename);
                $insura_logo_storage_path = 'images/' . config('insura.logo');
                if($insura_logo_filename !== config('insura.logo') && Storage::has($insura_logo_storage_path)) {
                    Storage::delete($insura_logo_storage_path);
                }
                $input['insura_logo'] = $insura_logo_filename;
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('settings.message.error.file', array(
                        'filename'  => $request->file('logo')->getClientOriginalName(),
                        'type'      => trans('settings.message.error.files.logo')
                    ))
                ));
            }
        }

        $env = view('templates.env', ['env' => $input]);
        Storage::disk('base')->put('.env', $env);
        Artisan::call('config:clear');

        return redirect()->action('SettingController@cache');
    }
    
    /**
     * Get all settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        $user = $request->user();
        $view_data = array();
        if(in_array($user->role, array('admin', 'super'))) {
            $view_data['company'] = $user->company;
            $view_data['reminders'] = $user->company->reminders->all();
        }
        return view($user->role . '.settings', $view_data);
    }
    
    /**
     * Load the app with new settings
     * 
     * @return \Illuminate\Http\Response
     */
    public function load() {
        return redirect()->action('SettingController@get')->with('success', trans('settings.message.success.system.edit'))->with('tab', 'system');
    }
}
