<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Insura is an online Insurance Agency Management System" name="description">
  <meta content="Simcy Creative" name="author">
  <title>{{ trans('auth.title.' . $state) }} - {{ config('insura.name') }} | Insurance Agency Management System</title>
  <base href="{{ env('BASE_HREF', '') }}" />
  <!-- Favicon -->
  <link href="{{ asset('favicon.ico') }}" rel="icon" sizes="16x16" type="image/x-icon">
  <link href="{{ asset('uploads/images/' . config('insura.favicon')) }}" rel="icon" type="{{ mime_content_type(storage_path() . '/app/images/' . config('insura.favicon')) }}">
  <!-- Font and Icon Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700" rel="stylesheet">
  <link href="{{ asset('assets/fonts/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
  <!-- Core CSS -->
  <link href="{{ asset('assets/libs/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/libs/semantic-UI/semantic.min.css') }}" rel="stylesheet">
  <!-- Page Specific CSS -->
  
  <!-- Custom CSS -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <!-- Extra Customization CSS -->
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="auth">
  <div class="auth-card">
    <div class="auth-branding">
      <img src="{{ asset('uploads/images/' . config('insura.favicon')) }}">
    </div>
    <div class="sign-in" style="{{ $state === 'login' ? '' : 'display:none;' }}">
      <h3>{{ trans('auth.header.login', array(
        'system_name' => config('insura.name')
      )) }}</h3>
      <p>{{ trans('auth.welcome.login') }}</p>
      @if ($state === 'login')
        @include('global.status', ['errors' => $errors->login])
      @endif
      <form action="{{ action('Auth\AuthController@postLogin') }}" method="POST">
        {!! csrf_field() !!}
        <div class="ui form">
          <div class="field required">
            <label>{{ trans('auth.input.label.email') }}</label>
            <input type="text" name="email" placeholder="{{ trans('auth.input.placeholder.email') }}" required value="{{ old('email') }}">
          </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.password') }}</label>
            <input type="password" name="password" placeholder="{{ trans('auth.input.placeholder.password') }}" required>
          </div>
          <div class="ui toggle checkbox">
            <input type="checkbox"{{ old('remember') ? ' checked' : '' }} id="remember" name="remember">
            <label for="remember">{{ trans('auth.input.label.remember') }}</label>
          </div>
          <div class="field text-right">
            <a href="" class="forgot auth-action" action="forgot-password">{{ trans('auth.link.forgot') }}</a>
          </div>
          <div class="field">
            <button class="ui button fluid positive" type="submit">{{ trans('auth.button.login') }}</button>
          </div>
        </div>
      </form>
      <p>{{ trans('auth.request.register') }} <a href="" class="text-primary auth-action" action="sign-up">{{ trans('auth.link.register') }}</a></p>
    </div>

    <div class="sign-up" style="{{ $state === 'register' ? '' : 'display:none;' }}">
      <h3>{{ trans('auth.header.register') }}</h3>
      <p>{{ trans('auth.welcome.register', array(
        'system_name' => config('insura.name')
      )) }}</p>
      @if ($state === 'register')
        @include('global.status', ['errors' => $errors->register])
      @endif
      <form action="{{ action('Auth\AuthController@postRegister') }}" method="POST">
        {!! csrf_field() !!}
        <div class="ui form">
            <div class="two fields">
              <div class="field required">
                <label>{{ trans('auth.input.label.first_name') }}</label>
                <input type="text" name="first_name" placeholder="{{ trans('auth.input.placeholder.first_name') }}" required value="{{ old('first_name') }}">
              </div>
              <div class="field">
                <label>{{ trans('auth.input.label.last_name') }}</label>
                <input type="text" name="last_name" placeholder="{{ trans('auth.input.placeholder.last_name') }}" value="{{ old('last_name') }}">
              </div>
            </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.email') }}</label>
            <input type="email" name="email" placeholder="{{ trans('auth.input.placeholder.email') }}" required value="{{ old('email') }}">
          </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.company_name') }}</label>
            <input type="text" name="company_name" placeholder="{{ trans('auth.input.placeholder.company_name') }}" required value="{{ old('company_name') }}">
          </div>
          <div class="two fields">
            <div class="field required">
              <label>{{ trans('auth.input.label.password') }}</label>
              <input type="password" name="password" placeholder="{{ trans('auth.input.placeholder.password') }}" required>
            </div>
            <div class="field required">            
              <label>{{ trans('auth.input.label.password_confirmation') }}</label>
              <input type="password" name="password_confirmation" placeholder="{{ trans('auth.input.placeholder.password_confirmation') }}" required>
            </div>
          </div>
          <div class="field">
            <button class="ui button fluid positive" type="submit">{{ trans('auth.button.register') }}</button>
          </div>
        </div>
      </form>
      <p>{{ trans('auth.request.login') }} <a href="" class="text-primary auth-action" action="sign-in">{{ trans('auth.link.login') }}</a></p>
    </div>

    <div class="forgot-password" style="{{ $state === 'forgot' ? '' : 'display:none;' }}">
      <h3>{{ trans('auth.header.forgot') }}</h3>
      <p>{{ trans('auth.welcome.forgot') }}</p>
      @if ($state === 'forgot')
        @include('global.status', ['errors' => $errors->forgot])
      @endif
      <form action="{{ action('Auth\PasswordController@postEmail') }}" method="POST">
        {!! csrf_field() !!}
        <div class="ui form">
          <div class="field required">
            <label>{{ trans('auth.input.label.email') }}</label>
            <input type="email" name="email" placeholder="{{ trans('auth.input.placeholder.email') }}" required value="{{ old('email') }}">
          </div>
          <div class="field">
            <button class="ui button fluid positive" type="submit">{{ trans('auth.button.forgot') }}</button>
          </div>
        </div>
      </form>
      <p>{{ trans('auth.request.login') }} <a href="" class="text-primary auth-action" action="sign-in">{{ trans('auth.link.login') }}</a></p>
    </div>

    @if($state === 'reset')
    <div class="reset" style="">
      <h3>{{ trans('auth.header.reset') }}</h3>
      <p>{{ trans('auth.welcome.reset') }}</p>
      @include('global.status', ['errors' => $errors->reset])
      <form action="{{ action('Auth\PasswordController@postReset') }}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token or '' }}">
        <div class="ui form">
          <div class="field required">
            <label>{{ trans('auth.input.label.email') }}</label>
            <input type="email" name="email" placeholder="{{ trans('auth.input.placeholder.email') }}" required value="{{ $email or old('email') }}"/>
          </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.password_reset') }}</label>
            <input type="password" name="password" placeholder="{{ trans('auth.input.placeholder.password_reset') }}" required/>
          </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.password_confirmation') }}</label>
            <input type="password" name="password_confirmation" placeholder="{{ trans('auth.input.placeholder.password_confirmation') }}" required/>
          </div>
          <div class="field">
            <button class="ui button fluid positive" type="submit">{{ trans('auth.button.reset') }}</button>
          </div>
        </div>
      </form>
      <p>{{ trans('auth.request.login') }} <a href="" class="text-primary auth-action" action="sign-in">{{ trans('auth.link.login') }}</a></p>
    </div>
    @endif

    @if($state === 'activate')
    <div class="activate" style="">
      <h3>{{ trans('auth.header.activate') }}</h3>
      <p>{{ trans('auth.welcome.activate') }}</p>
      @include('global.status', ['errors' => $errors->activate])
      <form action="{{ action('Auth\PasswordController@postActivate') }}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token or old('token') }}">
        <input type="hidden" name="email" value="{{ $email or old('email') }}"/>
        <div class="ui form">
          <div class="field required">
            <label>{{ trans('auth.input.label.password') }}</label>
            <input type="password" name="password" placeholder="{{ trans('auth.input.placeholder.password') }}" required/>
          </div>
          <div class="field required">
            <label>{{ trans('auth.input.label.password_confirmation') }}</label>
            <input type="password" name="password_confirmation" placeholder="{{ trans('auth.input.placeholder.password_confirmation') }}" required/>
          </div>
          <div class="field">
            <button class="ui button fluid positive" type="submit">{{ trans('auth.button.activate') }}</button>
          </div>
        </div>
      </form>
      <p>{{ trans('auth.request.login') }} <a href="" class="text-primary auth-action" action="sign-in">{{ trans('auth.link.login') }}</a></p>
    </div>
    @endif
    <div style="text-align:center;">
      <div class="button dropdown selection floating fluid icon labeled pointed search ui">
        <i class="world icon"></i>
        <span class="text">{{ trans('auth.input.label.language') }}</span>
        <div class="menu">
          @foreach(config('insura.languages') as $language)
          <a class="item" href="{{ action('Auth\AuthController@getAuth', array('language' => $language['locale'])) }}">{{ $language['name'] }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <!-- Core Scripts -->
  <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('assets/libs/semantic-UI/semantic.min.js') }}"></script>
  <script src="{{ asset('assets/js/insura.js') }}" type="text/javascript"></script>
  <!-- Page Specific Scripts -->
  <script src="{{ asset('assets/js/auth.js') }}"></script>
  <!-- Extra Scripts -->
  <script type="text/javascript">
    (function($insura, $) {
      $(document).ready(function() {
        $insura.helpers.initDropdown('div.dropdown');

        $('{!! array(
          'login'     => 'div.sign-in input[name="email"]',
          'register'  => 'div.sign-up input[name="first_name"]',
          'forgot'    => 'div.forgot-password input[name="email"]',
          'reset'     => 'div.reset input[name="password"]',
          'activate'  => 'div.activate input[name="password"]'
        )[$state] !!}').focus();
      });
    })(window.insura, window.jQuery);
  </script>
  
</body>
</html>
