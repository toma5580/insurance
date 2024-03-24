@extends('global.app')

@section('title', trans('staff.title.all'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
@endsection

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <button class="ui labeled icon button primary open-modal" data-target="#newStaffModal" data-toggle="modal">
                    <i class="ion-ios-plus-outline icon"></i> 
                    {{ trans('staff.button.new') }} 
                </button>
            </div>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="row clients-list">
            <!-- start staff -->
            @forelse($staff as $employee)
            <div class="col-md-6">
                <div class="ui segment white client-list-card">
                    <div class="client-list-about">
                        <div class="client-list-avatar">
                            @if ($employee->profile_image_filename === 'default-profile.jpg')
                            <div class="text-avatar small w-h-70" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($employee->first_name[0] . (isset($employee->last_name) ? $employee->last_name[0] : '')) }}</div>
                            @else
                            <img src="{{ asset('uploads/images/users/' . $employee->profile_image_filename) }}" alt="{{ $employee->first_name }} {{ $employee->last_name }}"/>
                            @endif
                        </div>
                        <div class="client-list-info">
                            <h3>{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                            <span>
                                @if ($employee->status)
                                <i class="ion-ios-circle-filled text-success"></i> {{ trans('staff.status.active') }}
                                @else
                                <i class="ion-ios-circle-filled text-danger"></i> {{ trans('staff.status.inactive') }}
                                @endif
                            </span>
                            <div class="client-list-contact">
                                <div class="col-xs-6 col-sm-6 col-md-6 b-r text-ellipsis p-0">
                                    <i class="ion-ios-email"></i> {{ $employee->email }}
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 text-ellipsis">
                                    <i class="ion-ios-telephone"></i> {{ $employee->phone or '(---) ---- --- ---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row client-list-footer">
                        <div class="col-xs-3 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('staff.label.commission') }}:</strong> {{ $employee->currency_symbol or $staff->currency_symbol }}{{ $employee->commission }}
                            </p>
                        </div>
                        <div class="col-xs-5 col-sm-4 col-md-4">
                            <p>
                                <strong>{{ trans('staff.label.sales') }}:</strong>
                                @if ($employee->sales > $employee->paid)
                                <span class="text-danger">{{ $employee->currency_symbol or $staff->currency_symbol }}{{ $employee->due }}</span>
                                @elseif ($employee->sales === $employee->paid)
                                <span class="text-success">{{ trans('staff.label.status.paid') }}</span>
                                @else
                                <span class="text-info">{{ $employee->currency_symbol or $staff->currency_symbol }}{{ $employee->due }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 text-right client-list-more">
                            <a href="{{ action('StaffController@getOne', array($employee->id)) }}" class="mini ui button"> {{ trans('staff.link.profile') }} </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-4 col-md-offset-3">
                <div class="segment text-center ui white">
                    <i class="huge icon ion-android-alert"></i>
                    <p>{{ trans('staff.message.empty') }}</p>
                </div>
            </div>
            @endforelse
            <!-- end staff -->
            <div class="col-md-12 text-center">
                {!! $staff->render($presenter) !!}
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
