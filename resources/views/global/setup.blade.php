<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Insura is an online Insurance Agency Management System" name="description">
    <meta content="Simcy Creative" name="author">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ trans('setup.title') }} - {{ config('insura.name') }} | Insurance Agency Management System</title>
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
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
    	div.page-title {
    		text-align: center;
    	}
        div.message p > span {
            margin: -.2em 0 0 .2em;
            color: #db2828;
        }
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="container">
        <div class="page-title with-desc">
  			<img alt="Logo" src="{{ asset('uploads/images/' . config('insura.favicon')) }}" /><br/>
            <h2>{{ trans('setup.title') }}</h2>
            <p>{{ trans('setup.sub_title') }}</p>
        </div>
        <div class="ui info icon message">
            <i class="ion-android-alert icon"></i>
            <i class="close icon"></i>
            <div class="content">
                <p>{{ trans('setup.message.info') }}<span>*</span></p>
            </div>
        </div>
        @include('global.status')
        <form action="{{ action('SetupController@configure') }}" enctype="multipart/form-data" method="POST">
        	{{ csrf_field() }}
	        <div class="ui top attached tabular menu insura-tabs">
	            <a class="item active" data-tab="step01">{{ trans('setup.tab.menu.step01') }}</a>
	            <a class="item" data-tab="step02">{{ trans('setup.tab.menu.step02') }}</a>
	            <a class="item" data-tab="step03">{{ trans('setup.tab.menu.step03') }}</a>
	            <a class="item" data-tab="step04">{{ trans('setup.tab.menu.step04') }}</a>
	            <a class="item" data-tab="step05">{{ trans('setup.tab.menu.step05') }}</a>
	        </div>
	        <div class="ui bottom attached tab segment active" data-tab="step01">
	            <div class="row">
	                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
	                    <p>{{ trans('setup.tab.message.step01') }}</p>
	                    <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('setup.input.label.db_default') }}</label>
                                <select class="ui fluid search dropdown" name="db_connection">
                                    <option{{ config('database.default') === 'mysql' ? ' selected' : '' }} value="mysql">{{ trans('setup.input.option.db_default.mysql') }}</option>
                                    <option{{ config('database.default') === 'pgsql' ? ' selected' : '' }} value="pgsql">{{ trans('setup.input.option.db_default.pgsql') }}</option>
                                    <option{{ config('database.default') === 'sqlite' ? ' selected' : '' }} value="sqlite">{{ trans('setup.input.option.db_default.sqlite') }}</option>
                                    <option{{ config('database.default') === 'sqlsrv' ? ' selected' : '' }} value="sqlsrv">{{ trans('setup.input.option.db_default.sqlsrv') }}</option>
                                </select>
                            </div>
                            <div class="field db mysql pgsql sqlsrv" style="display:none;">
                                <label>{{ trans('setup.input.label.db_host') }}</label>
                                <input type="text" name="db_host" placeholder="{{ trans('setup.input.placeholder.db_host') }}" required value="{{ config('database.connections.' . config('database.default') . '.host') }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.db_database') }}</label>
                                <input type="text" name="db_database" placeholder="{{ trans('setup.input.placeholder.db_database') }}" required value="{{ config('database.connections.' . config('database.default') . '.database') }}">
                            </div>
                            <div class="field db mysql pgsql sqlsrv" style="display:none;">
                                <label>{{ trans('setup.input.label.db_username') }}</label>
                                <input type="text" name="db_username" placeholder="{{ trans('setup.input.placeholder.db_username') }}" value="{{ config('database.connections.' . config('database.default') . '.username') }}">
                            </div>
                            <div class="field db mysql pgsql sqlsrv" style="display:none;">
                                <label>{{ trans('setup.input.label.db_password') }}</label>
                                <input type="password" name="db_password" placeholder="{{ trans('setup.input.placeholder.db_password') }}">
                            </div>
                            <div class="field db mysql pgsql sqlsrv" style="display:none;">
                                <label>{{ trans('setup.input.label.confirm_password') }}</label>
                                <input type="password" name="db_password_confirmation" placeholder="{{ trans('setup.input.placeholder.confirm_password') }}">
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <button class="ui right floated button primary m-w-140" data-target="step02" data-toggle="tab" type="button">{{ trans('setup.button.next') }}</button>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	        <div class="ui bottom attached tab segment" data-tab="step02">
	            <div class="row">
	                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
	                    <p>{{ trans('setup.tab.message.step02') }}</p>
	                    <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('setup.input.label.mail_username') }}</label>
                                <input type="text" name="mail_username" placeholder="{{ trans('setup.input.placeholder.mail_username') }}" required value="{{ config('mail.username') }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.mail_driver') }}</label>
                                <select class="ui fluid search dropdown" name="mail_driver">
                                    <option{{ config('mail.driver') === 'mailgun' ? ' selected' : '' }} value="mailgun">{{ trans('setup.input.option.mail_driver.mailgun') }}</option>
                                    <option{{ config('mail.driver') === 'mandrill' ? ' selected' : '' }} value="mandrill">{{ trans('setup.input.option.mail_driver.mandrill') }}</option>
                                    <option{{ config('mail.driver') === 'sendmail' ? ' selected' : '' }} value="sendmail">{{ trans('setup.input.option.mail_driver.sendmail') }}</option>
                                    <option{{ config('mail.driver') === 'ses' ? ' selected' : '' }} value="ses">{{ trans('setup.input.option.mail_driver.ses') }}</option>
                                    <option{{ config('mail.driver') === 'smtp' ? ' selected' : '' }} value="smtp">{{ trans('setup.input.option.mail_driver.smtp') }}</option>
                                </select>
                            </div>
                            <div class="field mail mailgun" style="display:none;">
                                <label>{{ trans('setup.input.label.mailgun_domain') }}</label>
                                <input type="text" name="mailgun_domain" placeholder="{{ trans('setup.input.placeholder.mailgun_domain') }}" value="{{ config('services.mailgun.domain') }}">
                            </div>
                            <div class="field mail mailgun" style="display:none;">
                                <label>{{ trans('setup.input.label.mailgun_secret') }}</label>
                                <input type="text" name="mailgun_secret" placeholder="{{ trans('setup.input.placeholder.mailgun_secret') }}" value="{{ config('services.mailgun.secret') }}">
                            </div>
                            <div class="field mail mandrill" style="display:none;">
                                <label>{{ trans('setup.input.label.mandrill_secret') }}</label>
                                <input type="text" name="mandrill_secret" placeholder="{{ trans('setup.input.placeholder.mandrill_secret') }}" value="{{ config('services.mandrill.secret') }}">
                            </div>
                            <div class="field mail sendmail" style="display:none;">
                                <label>{{ trans('setup.input.label.sendmail_path') }}</label>
                                <input type="text" name="sendmail_path" placeholder="{{ trans('setup.input.placeholder.sendmail_path') }}" value="{{ config('mail.sendmail') }}">
                            </div>
                            <div class="field mail ses" style="display:none;">
                                <label>{{ trans('setup.input.label.ses_key') }}</label>
                                <input type="text" name="ses_key" placeholder="{{ trans('setup.input.placeholder.ses_key') }}" value="{{ config('services.ses.key') }}">
                            </div>
                            <div class="field mail ses" style="display:none;">
                                <label>{{ trans('setup.input.label.ses_region') }}</label>
                                <input type="text" name="ses_region" placeholder="{{ trans('setup.input.placeholder.ses_region') }}" value="{{ config('services.ses.region') }}">
                            </div>
                            <div class="field mail ses" style="display:none;">
                                <label>{{ trans('setup.input.label.ses_secret') }}</label>
                                <input type="text" name="ses_secret" placeholder="{{ trans('setup.input.placeholder.ses_secret') }}" value="{{ config('services.ses.secret') }}">
                            </div>
                            <div class="field mail smtp" style="display:none;">
                                <label>{{ trans('setup.input.label.smtp_host') }}</label>
                                <input type="text" name="smtp_host" placeholder="{{ trans('setup.input.placeholder.smtp_host') }}" value="{{ config('mail.host') }}">
                            </div>
                            <div class="field mail smtp" style="display:none;">
                                <label>{{ trans('setup.input.label.smtp_port') }}</label>
                                <input type="number" name="smtp_port" placeholder="{{ trans('setup.input.placeholder.smtp_port') }}" step="1" value="{{ config('mail.port') }}">
                            </div>
                            <div class="field mail smtp" style="display:none;">
                                <label>{{ trans('setup.input.label.smtp_password') }}</label>
                                <input type="password" maxlength="64" name="smtp_password" placeholder="{{ trans('setup.input.placeholder.smtp_password') }}">
                            </div>
                            <div class="field mail smtp" style="display:none;">
                                <label>{{ trans('setup.input.label.confirm_password') }}</label>
                                <input type="password" maxlength="64" name="smtp_password_confirmation" placeholder="{{ trans('setup.input.placeholder.confirm_password') }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.mail_encryption') }}</label>
                                <select class="ui fluid search dropdown" name="mail_encryption">
                                    <option{{ config('mail.encryption') === 'none' ? ' selected' : '' }} value="none">{{ trans('setup.input.option.mail_encryption.none') }}</option>
                                    <option{{ config('mail.encryption') === 'ssl' ? ' selected' : '' }} value="ssl">{{ trans('setup.input.option.mail_encryption.ssl') }}</option>
                                    <option{{ config('mail.encryption') === 'tls' ? ' selected' : '' }} value="tls">{{ trans('setup.input.option.mail_encryption.tls') }}</option>
                                </select>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <button class="ui left floated button primary m-w-140" data-target="step01" data-toggle="tab" type="button">{{ trans('setup.button.prevoius') }}</button>
                                <button class="ui right floated button primary m-w-140" data-target="step03" data-toggle="tab" type="button">{{ trans('setup.button.next') }}</button>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	        <div class="ui bottom attached tab segment" data-tab="step03">
	            <div class="row">
	                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
	                    <p>{{ trans('setup.tab.message.step03') }}</p>
	                    <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('setup.input.label.url') }}</label>
                                <input type="url" list="appUrl" name="app_url" placeholder="{{ trans('setup.input.placeholder.url') }}" required>
                                <datalist id="appUrl">
                                    <option value="{{ url('/') }}" label="Current URL">
                                    <option value="{{ $alternate_url }}" label="Alternate URL">
                                </datalist>
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.name') }}</label>
                                <input type="text" maxlength="64" minlength="3" name="insura_name" placeholder="{{ trans('setup.input.placeholder.name') }}" required value="{{ config('insura.name') }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.locale') }}</label>
                                <select class="ui fluid search dropdown" name="app_locale_default">
                                    @foreach(config('insura.languages') as $language)
                                    <option{{ config('app.locale') === $language['locale'] ? ' selected' : '' }} value="{{ $language['locale'] }}">{{ $language['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.currency') }}</label>
                                <select class="ui fluid search dropdown" name="insura_currency_default">
                                    @foreach(config('insura.currencies.list') as $currency)
                                    <option{{ config('insura.currencies.default') === $currency['code'] ? ' selected' : '' }} value="{{ $currency['code'] }}">{{ $currency['name_plural'] }} ({{ $currency['code'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.logo') }}</label>
                                <input type="file" accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-allowed-formats="landscape" data-default-file="{{ asset('uploads/images/' . config('insura.logo')) }}" name="logo"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.favicon') }}</label>
                                <input type="file" accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-allowed-formats="square" data-default-file="{{ asset('uploads/images/' . config('insura.favicon')) }}" name="favicon">
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <button class="ui left floated button primary m-w-140" data-target="step02" data-toggle="tab" type="button">{{ trans('setup.button.prevoius') }}</button>
                                <button class="ui right floated button primary m-w-140" data-target="step04" data-toggle="tab" type="button">{{ trans('setup.button.next') }}</button>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	        <div class="ui bottom attached tab segment" data-tab="step04">
	            <div class="row">
	                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
	                    <p>{{ trans('setup.tab.message.step04') }}</p>
                        <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('setup.input.label.name') }}</label>
                                <input type="text" maxlength="64" name="company_name" placeholder="{{ trans('setup.input.placeholder.name') }}" required value="{{ old('company_name') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.email') }}</label>
                                <input type="email" maxlength="64" name="company_email" placeholder="{{ trans('setup.input.placeholder.email') }}" value="{{ old('company_email') }}">
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.address') }}</label>
                                <input type="text" maxlength="256" name="company_address" placeholder="{{ trans('setup.input.placeholder.address') }}" value="{{ old('company_address') }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.currency') }}</label>
                                <select class="ui fluid search dropdown" name="currency_code">
                                    @foreach(config('insura.currencies.list') as $currency)
                                    <option{{ old('currency_code') === $currency['code'] ? ' selected' : '' }} value="{{ $currency['code'] }}">{{ $currency['name_plural'] }} ({{ $currency['code'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.email_signature') }}</label>
                                <textarea name="email_signature" rows="9">{{ old('email_signature') }}</textarea>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.text_signature') }}</label>
                                <textarea maxlength="32" name="text_signature" rows="2">{{ old('text_signature') }}</textarea>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.text_provider') }}</label>
                                <select class="ui fluid search dropdown" name="text_provider">
                                    <option value="">Please Select</option>
                                    <option{{ old('text_provider') === 'aft' ? ' selected' : '' }} value="aft">Africa's Talking</option>
                                    <option{{ old('text_provider') === 'twilio' ? ' selected' : '' }} value="twilio">Twilio</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.twilio_auth_token') }}</label>
                                <input type="text" maxlength="64" name="twilio_auth_token" placeholder="{{ trans('setup.input.placeholder.twilio_auth_token') }}" value="{{ old('twilio_auth_token') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.twilio_number') }}</label>
                                <input type="text" maxlength="32" name="twilio_number" placeholder="{{ trans('setup.input.placeholder.twilio_number') }}" value="{{ old('twilio_number') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.twilio_sid') }}</label>
                                <input type="text" maxlength="64" name="twilio_sid" placeholder="{{ trans('setup.input.placeholder.twilio_sid') }}" value="{{ old('twilio_sid') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.aft_api_key') }}</label>
                                <input type="text" maxlength="64" name="aft_api_key" placeholder="{{ trans('setup.input.placeholder.aft_api_key') }}" value="{{ old('aft_api_key') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.aft_username') }}</label>
                                <input type="text" maxlength="64" name="aft_username" placeholder="{{ trans('setup.input.placeholder.aft_username') }}" value="{{ old('aft_username') }}"/>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <button class="ui left floated button primary m-w-140" data-target="step03" data-toggle="tab" type="button">{{ trans('setup.button.prevoius') }}</button>
                                <button class="ui right floated button primary m-w-140" data-target="step05" data-toggle="tab" type="button">{{ trans('setup.button.next') }}</button>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	        <div class="ui bottom attached tab segment" data-tab="step05">
	            <div class="row">
	                <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3">
	                    <p>{{ trans('setup.tab.message.step05') }}</p>
                        <div class="ui form">
                        	<div class="two fields">
	                            <div class="field required">
	                                <label>{{ trans('setup.input.label.first_name') }}</label>
	                                <input type="text" name="first_name" placeholder="{{ trans('setup.input.placeholder.first_name') }}" required value="{{ old('first_name') }}"/>
	                            </div>
	                            <div class="field">
	                                <label>{{ trans('setup.input.label.last_name') }}</label>
	                                <input type="text" name="last_name" placeholder="{{ trans('setup.input.placeholder.last_name') }}" value="{{ old('last_name') }}"/>
	                            </div>
	                        </div>
                            <div class="field required">
                                <label>{{ trans('setup.input.label.email') }}</label>
                                <input type="email" name="account_email" placeholder="{{ trans('setup.input.placeholder.email') }}" required value="{{ old('account_email') }}"/>
                            </div>
                            <div class="two fields">
	                            <div class="field required">
	                                <label>{{ trans('setup.input.label.password') }}</label>
	                                <input type="password" name="password" placeholder="{{ trans('setup.input.placeholder.password') }}" required/>
	                            </div>
	                            <div class="field required">
	                                <label>{{ trans('setup.input.label.confirm_password') }}</label>
	                                <input type="password" name="password_confirmation" placeholder="{{ trans('setup.input.placeholder.confirm_password') }}" required/>
	                            </div>
	                        </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.phone') }}</label>
                                <input type="tel" placeholder="{{ trans('setup.input.placeholder.phone') }}" value="{{ old('phone') }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.address') }}</label>
                                <input type="text" maxlength="256" name="account_address" placeholder="{{ trans('setup.input.placeholder.address') }}" value="{{ old('account_address') }}">
                            </div>
                            <div class="two fields">
	                            <div class="field">
	                                <label>{{ trans('setup.input.label.birthday') }}</label>
	                                <input type="date" class="datepicker" name="birthday" placeholder="{{ trans('setup.input.placeholder.birthday') }}" value="{{ old('birthday') }}"/>
	                            </div>
	                            <div class="field required">
	                                <label>{{ trans('setup.input.label.locale') }}</label>
	                                <select class="ui fluid search dropdown" name="locale">
	                                    @foreach(config('insura.languages') as $language)
	                                    <option{{ old('locale') === $language['locale'] ? ' selected' : '' }} value="{{ $language['locale'] }}">{{ $language['name'] }}</option>
	                                    @endforeach
	                                </select>
	                            </div>
	                        </div>
                            <div class="field">
                                <label>{{ trans('setup.input.label.profile_image') }}</label>
                                <input type="file"  accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image">
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <button class="ui left floated button primary m-w-140" data-target="step04" data-toggle="tab" type="button">{{ trans('setup.button.prevoius') }}</button>
                                <button class="ui right floated button primary m-w-140" type="submit">{{ trans('setup.button.finish') }}</button>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	    </form>
	    <div class="row">
			<div class="col-xs-8 col-xs-offset-2 col-md-4 col-md-offset-4">
				<div class="ui floating fluid dropdown selection labeled search icon button ui">
					<i class="world icon"></i>
					<span class="text">{{ trans('auth.input.label.language') }}</span>
					<div class="menu">
						@foreach(config('insura.languages') as $language)
						<a class="item" href="{{ action('SetupController@configure', array('language' => $language['locale'])) }}">{{ $language['name'] }}</a>
						@endforeach
					</div>
				</div>
			</div>
	    </div>
    </div>

    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/semantic-UI/semantic.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/insura.js') }}" type="text/javascript"></script>
    <!-- Page Specific Scripts -->
    <script src="{{ asset('assets/libs/datepicker/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/intl-tel-input/js/intlTelInput.min.js') }}" type="text/javascript"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initTabs('div.insura-tabs a.item');
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.requireDropdownFields('form div.required select');

                // Navigate Tabs
                $('button[data-toggle="tab"]').click(function() {
                	var tab = $(this).attr('data-target');
                	$('div.insura-tabs a.item[data-tab="' + tab + '"]').click();
                	$('div.tab[data-tab="' + tab + '"] input:first').focus();
                });

                // Toggle Database Driver
                $('select[name="db_connection"]').change(function(event) {
                	$('div.db').each(function(i, e) {
                		var field = $(e);
                		if(field.hasClass(event.target.value)) {
                			field.show();
                		}else {
                			field.hide();
                		}
                	});
                }).change();

                // Toggle Mail Driver
                $('select[name="mail_driver"]').change(function() {
                    var select = $(this).fadeOut(100);
                    $('div.field.mail').attr('required', false).hide();
                    $('div.mail.' + select.val()).attr('required', true).fadeIn(200);
                }).change();

                // Toggle Text Providers
                $('select[name="text_provider"]').change(function() {
                    var element = $(this);
                    var parentTab = element.parents('div.segment.tab:first'),
                        value = element.val();
                    if(value === 'aft') {
                        parentTab.find('input[name^="aft_"]').attr('required', true).parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="aft_"]').attr('required', false).parent().fadeOut();
                    }
                    if(value === 'twilio') {
                        parentTab.find('input[name^="twilio_"]').attr('required', true).parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="twilio_"]').attr('required', false).parent().fadeOut();
                    }
                }).change();
            });
        })(window.insura, window.jQuery);
    </script>

</body>
</html>
