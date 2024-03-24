@extends('global.app')

@section('title', trans('settings.title'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="ui top attached tabular menu insura-tabs">
            <a class="item{{ session()->get('tab', 'profile') === 'profile' ? ' active' : null }}" data-tab="profile">{{ trans('settings.tab.menu.profile') }}</a>

            @yield('company_menu')

            @yield('system_menu')

            @yield('reminders_menu')

            <a class="item{{ session()->get('tab', null) === 'security' ? ' active' : null }}" data-tab="security">{{ trans('settings.tab.menu.security') }}</a>
        </div>
        <div class="ui bottom attached tab segment{{ session()->has('tab') ? null : ' active' }}" data-tab="profile">
            <div class="row">
                <div class="col-md-6">
                    <p>{{ trans('settings.tab.message.profile') }}</p>
                    <form action="{{ action('UserController@edit', array($user->id)) }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('settings.input.label.first_name') }}</label>
                                <input type="text" name="first_name" placeholder="{{ trans('settings.input.placeholder.first_name') }}" required value="{{ $user->first_name }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.last_name') }}</label>
                                <input type="text" name="last_name" placeholder="{{ trans('settings.input.placeholder.last_name') }}" value="{{ $user->last_name }}"/>
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.email') }}</label>
                                <input type="email" name="email" placeholder="{{ trans('settings.input.placeholder.email') }}" required value="{{ $user->email }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.phone') }}</label>
                                <input type="tel" placeholder="{{ trans('settings.input.placeholder.phone') }}" value="{{ $user->phone }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.birthday') }}</label>
                                <input type="date" class="datepicker" name="birthday" placeholder="{{ trans('settings.input.placeholder.birthday') }}" value="{{ $user->birthday }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.address') }}</label>
                                <input type="text" name="address" placeholder="{{ trans('settings.input.placeholder.address') }}" value="{{ $user->address }}"/>
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.locale') }}</label>
                                <select class="ui fluid search dropdown" name="locale">
                                    @foreach(config('insura.languages') as $language)
                                    <option{{ $user->locale === $language['locale'] ? ' selected' : '' }} value="{{ $language['locale'] }}">{{ $language['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.profile_image') }}</label>
                                <input type="file"  accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-default-file="{{ asset('uploads/images/users/' . $user->profile_image_filename) }}" name="profile_image">
                            </div>
                            <div class="field">
                                <button class="ui right floated button primary m-w-140" type="submit">{{ trans('settings.button.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @yield('company_tab')

        @yield('system_tab')

        @yield('reminders_tab')

        <div class="ui bottom attached tab segment{{ session()->get('tab', null) === 'security' ? ' active' : '' }}" data-tab="security">
            <div class="row">
                <div class="col-md-6">
                    <p>{{ trans('settings.tab.message.security') }}</p>
                    <form action="{{ action('Auth\PasswordController@update') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('settings.input.label.current_password') }}</label>
                                <input type="password" name="current_password" placeholder="{{ trans('settings.input.placeholder.current_password') }}" required/>
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.new_password') }}</label>
                                <input type="password" name="new_password" placeholder="{{ trans('settings.input.placeholder.new_password') }}" required/>
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.confirm_password') }}</label>
                                <input type="password" name="new_password_confirmation" placeholder="{{ trans('settings.input.placeholder.confirm_password') }}" required/>
                            </div>
                            <div class="field">
                                <button class="ui right floated button primary m-w-140" type="submit">{{ trans('settings.button.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/datepicker/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/intl-tel-input/js/intlTelInput.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initAccordion('div.ui.accordion');
                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initTabs('div.insura-tabs a.item');
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select');
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
