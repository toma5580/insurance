@extends('global.app')

@section('title', trans('clients.title.all'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
@endsection

@section('extra_stylesheets')
    <link href="{{ asset('assets/css/split-page.css') }}" rel="stylesheet">
@endsection

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <button class="ui labeled icon button primary open-modal" data-target="#newClientModal" data-toggle="modal">
                    <i class="ion-ios-plus-outline icon"></i> 
                    {{ trans('clients.button.new') }} 
                </button>
            </div>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="row clients-list">
            <!-- start clients -->
            @forelse($clients as $client)
            <div class="col-md-6">
                <div class="ui segment white client-list-card">
                    <div class="client-list-about">
                        <div class="client-list-avatar">
                            @if ($client->profile_image_filename === 'default-profile.jpg')
                            <div class="text-avatar small w-h-70" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($client->first_name[0] . (isset($client->last_name) ? $client->last_name[0] : '')) }}</div>
                            @else
                            <img src="{{ asset('uploads/images/users/' . $client->profile_image_filename) }}" alt="{{ $client->first_name }} {{ $client->last_name }}"/>
                            @endif
                        </div>
                        <div class="client-list-info">
                            <h3>{{ $client->first_name }} {{ $client->last_name }}</h3>
                            <span>
                                @if ($client->status)
                                <i class="ion-ios-circle-filled text-success"></i> {{ trans('clients.status.active') }}
                                @else
                                <i class="ion-ios-circle-filled text-danger"></i> {{ trans('clients.status.inactive') }}
                                @endif
                            </span>
                            <div class="client-list-contact">
                                <div class="col-xs-6 col-sm-6 col-md-6 b-r text-ellipsis p-0">
                                    <i class="ion-ios-email"></i> {{ $client->email }}
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 text-ellipsis">
                                    <i class="ion-ios-telephone"></i> {{ $client->phone or '(---) ---- --- ---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row client-list-footer">
                        <div class="col-xs-3 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('clients.label.policies') }}:</strong> {{ $client->policies->count() }}
                            </p>
                        </div>
                        <div class="col-xs-5 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('clients.label.due') }}:</strong>
                                @if ($client->premiums > $client->paid)
                                <span class="text-danger">
                                @elseif ($client->premiums === $client->paid)
                                <span class="text-success">{{ trans('clients.label.status.paid') }} - 
                                @else
                                <span class="text-info">
                                @endif
                                    {{ $client->currency_symbol or $clients->currency_symbol }}{{ $client->due }}
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 text-right client-list-more">
                            <a href="{{ action('ClientController@getOne', array($client->id)) }}" class="mini ui button"> {{ trans('clients.link.profile') }} </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-4 col-md-offset-3">
                <div class="segment text-center ui white">
                    <i class="huge icon ion-android-alert"></i>
                    <p>{{ trans('clients.message.empty.clients') }}</p>
                </div>
            </div>
            @endforelse
            <!-- end clients -->
            <div class="col-md-12 text-center">
                {!! $clients->render($presenter) !!}
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
                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initModal('div.modal', true);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select, form div.required div.dropdown input[type="hidden"]');
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
