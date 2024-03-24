<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Validator;

class AuthController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Redirect path for failed logins.
     *
     * @var string
     */
    protected $loginPath;

    /**
     * Redirect path for successful logouts.
     *
     * @var string
     */
    protected $redirectAfterLogout;

    /**
     * Redirect path for successful logins.
     *
     * @var string
     */
    protected $redirectPath;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', array(
            'except' => 'getLogout'
        ));
        $this->middleware('localize_auth', array(
            'only' => 'getLogout'
        ));
        $this->middleware('localize_guest', array(
            'except' => 'getLogout'
        ));
        $this->loginPath = action('Auth\AuthController@getAuth');
        $this->redirectAfterLogout = action('Auth\AuthController@getAuth');
        $this->redirectPath = action('IndexController@getDashboard');
    }

    /**
     * Create the response for when a request fails validation. !!OVERRIDE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors) {
        if ($request->ajax() || $request->wantsJson()) {
            return new JsonResponse($errors, 422);
        }

        return redirect()->to($this->getRedirectUrl())
                        ->with('state', $this->errorBag())
                        ->withInput($request->input())
                        ->withErrors($errors, $this->errorBag());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        $company = Company::create(array(
            'name' => $data['company_name']
        ));
        $locale = config('app.locale');
        $admin = $company->admin()->create(array(
            'email'         => $data['email'],
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'locale'        => $locale
        ));
        $admin->password = bcrypt($data['password']);
        $admin->save();
        return $admin;
    }

    /**
     * Show the application authentification forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuth() {
        return view('global.auth');
    }

    /**
     * Handle a login request to the application. !!OVERRIDE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
        $this->validatesRequestErrorBag = 'login';

        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ], 'login');
    }

    /**
     * Redirect the user after determining they are locked out. !!OVERRIDE
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request) {
        $seconds = app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getLockoutErrorMessage($seconds),
            ], 'login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        $this->validatesRequestErrorBag = 'register';
        
        return Validator::make($data, [
            'company_name' => 'required|max:64',
            'email' => 'required|email|max:64|unique:users',
            'first_name' => 'required|max:32',
            'last_name' => 'max:32',
            'password' => 'required|confirmed|max:16|min:6'
        ]);
    }
}
