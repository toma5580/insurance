@extends('global.app')

@section('title', trans('brokers.title.all'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
@endsection

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <button class="ui labeled icon button primary open-modal" data-target="#newBrokerModal" data-toggle="modal">
                    <i class="ion-ios-plus-outline icon"></i> 
                    {{ trans('brokers.button.new') }} 
                </button>
            </div>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="row clients-list">
            <!-- start broker -->
            @forelse($brokers as $broker)
            <div class="col-md-6">
                <div class="ui segment white client-list-card">
                    <div class="client-list-about">
                        <div class="client-list-avatar">
                            @if ($broker->profile_image_filename === 'default-profile.jpg')
                            <div class="text-avatar small w-h-70" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($broker->first_name[0] . (isset($broker->last_name) ? $broker->last_name[0] : '')) }}</div>
                            @else
                            <img src="{{ asset('uploads/images/users/' . $broker->profile_image_filename) }}" alt="{{ $broker->first_name }} {{ $broker->last_name }}"/>
                            @endif
                        </div>
                        <div class="client-list-info">
                            <h3>{{ $broker->first_name }} {{ $broker->last_name }}</h3>
                            <span>
                                @if ($broker->status)
                                <i class="ion-ios-circle-filled text-success"></i> {{ trans('brokers.status.active') }}
                                @else
                                <i class="ion-ios-circle-filled text-danger"></i> {{ trans('brokers.status.inactive') }}
                                @endif
                            </span>
                            <div class="client-list-contact">
                                <div class="col-xs-6 col-sm-6 col-md-6 b-r text-ellipsis p-0">
                                    <i class="ion-ios-email"></i> {{ $broker->email }}
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 text-ellipsis">
                                    <i class="ion-ios-telephone"></i> {{ $broker->phone or '(---) ---- --- ---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row client-list-footer">
                        <div class="col-xs-3 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('brokers.label.commission') }}:</strong> 
                                @if ($broker->sales > $broker->paid)
                                <span class="text-danger">
                                @elseif ($broker->sales === $broker->paid)
                                <span class="text-success">{{ trans('brokers.label.status.paid') }} - 
                                @else
                                <span class="text-warning">
                                @endif
                                    {{ $broker->currency_symbol or $brokers->currency_symbol }}{{ $broker->commission }}
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-5 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('brokers.label.sales') }}:</strong>
                                @if ($broker->sales > $broker->paid)
                                <span class="text-danger">
                                @elseif ($broker->sales === $broker->paid)
                                <span class="text-success">
                                @else
                                <span class="text-warning">
                                @endif
                                    <strong>{{ $broker->currency_symbol or $brokers->currency_symbol }}{{ $broker->sales }}</strong>
                                </span>
                            </p>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 text-right client-list-more">
                            <a href="{{ action('BrokerController@getOne', array($broker->id)) }}" class="mini ui button"> {{ trans('brokers.link.profile') }} </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-4 col-md-offset-3">
                <div class="segment text-center ui white">
                    <i class="huge icon ion-android-alert"></i>
                    <p>{{ trans('brokers.message.empty') }}</p>
                </div>
            </div>
            @endforelse
            <!-- end broker -->
            <div class="col-md-12 text-center">
                {!! $brokers->render($presenter) !!}
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
