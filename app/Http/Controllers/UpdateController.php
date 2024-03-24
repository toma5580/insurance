<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Cache;
use Illuminate\Http\Request;
use Storage;

class UpdateController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Update Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing settings. Why
    | don't you explore it?
    |
    */

    /**
     * All published versions of insura in order
     *
     * @var array
     */
    protected $versions = array('2.0.0');

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
     * Re-cache the app
     * 
     * @return void
     */
    protected function cache() {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
    }
    
    /**
     * Get the update page
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        if($request->user()->role === 'super') {
            config(array(
                'app.debug' => true
            ));
            $this->prepare();
            return $this->install();
        }else {
            return redirect()->action('IndexController@get')->with('error', trans('setup.message.error.unauthorised'));
        }
    }
    
    /**
     * Install updates
     * 
     * @return \Illuminate\Http\Response
     */
    protected function install() {
        Artisan::call('migrate', array(
            '--force'   => true
        ));
        $current_version = $initial_version = Cache::get('INSURA_VERSION', '2.0.4');
        $update_versions = array_slice($this->versions, array_search($current_version, $this->versions) + 1);
        foreach($update_versions as $update_version) {
            include_once base_path() . "/app/Updates/{$update_version}.php";
            $current_version = $update_version;
            Cache::forever('INSURA_VERSION', $update_version);
        }
        $this->cache();
        
        return redirect()->action('UpdateController@load', array($current_version === $initial_version ? '0' : '1'));
    }
    
    /**
     * Load updates
     * 
     * @param string $status
     * @return \Illuminate\Http\Response
     */
    protected function load($status) {
        return redirect()->action('SettingController@get')->with('success', trans($status === '0' ? 'setup.message.success.same' : 'setup.message.success.update', array(
            'version'   => Cache::get('INSURA_VERSION', '2.0.4')
        )))->with('tab', 'system');
    }
    
    /**
     * Prepare for the update by clearing config & route caches
     * 
     * @return void
     */
    protected function prepare() {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        foreach(Storage::disk('base')->files('app/Updates') as $filename) {
            array_push($this->versions, str_replace(array(
                'app/Updates/',
                '.php'
            ), '', $filename));
        }
    }
}
