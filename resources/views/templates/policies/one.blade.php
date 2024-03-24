@extends('global.app')

@section('title', trans('policies.title.one'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('extra_stylesheets')
    <link href="{{ asset('assets/css/split-page.css') }}" rel="stylesheet"/>
@endsection

@section('profile_bar')
        <!-- client profile -->
        <div class="ui segment white right-bar-profile right-bar-profile-bottom">
            <div class="user-profile m-b-15">
                @if ($policy->client->profile_image_filename === 'default-profile.jpg')
                <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($policy->client->first_name[0] . $policy->client->last_name[0]) }}</div>
                @else
                <img src="{{ asset('uploads/images/users/' . $policy->client->profile_image_filename) }}" alt="{{ $policy->client->first_name . ' ' . $policy->client->last_name }}"/>
                @endif
                <h3>{{ $policy->client->first_name . ' ' . $policy->client->last_name }}</h3>
                <span>
                    @if ($policy->client->password === 'InsuraPasswordsAreLongButNeedToBeSetByInvitedUsersSuchAsThis')
                    <i class="ion-ios-circle-filled text-danger"></i> {{ trans('clients.status.inactive') }}
                    @else
                    <i class="ion-ios-circle-filled text-success"></i> {{ trans('clients.status.active') }}
                    @endif
                </span>
                @yield('client_action')
            </div>
            <div class="scrollbar">
                <div class="user-more-data">
                    <div class="divider m-t-0"></div>
                    <!-- client details -->
                    <div class="user-contact">
                        <div>
                            <span>{{ trans('clients.label.email') }}</span>
                            <p>{{ $policy->client->email }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.phone') }}</span>
                            <p>{{ $policy->client->phone }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.birthday') }}</span>
                            <p>{{ is_null($policy->client->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($policy->client->birthday)) }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.address') }}</span>
                            <p>{{ $policy->client->address or '. . .' }}</p>
                        </div>
                    </div>
                    <!-- end client details -->
                </div>
            </div>
        </div>
        <!-- end client profile -->
@endsection

@section('content')
        @parent
        <!-- half page content -->
        <div class="half-page-content">
            @include('global.status')
            <!-- Policy details -->
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('policies.label.details') }}</h3>
                    @yield('policy_actions')
                </div>
                <div class="policy-details">
                    <div class="row">
                        <div class="col-sm-3 col-md-4">
                            <span>{{ trans('policies.label.ref_no') }}</span>
                            <p>{{ $policy->ref_no }}</p>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <span>{{ trans('policies.label.product') }}</span>
                            <p>{{ $policy->product->name }}</p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <span class="ui blue right ribbon huge label">{{ trans("policies.ribbon.type.{$policy->type}") }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <span>{{ trans('policies.label.renewal') }}</span>
                            <p>{{ date('F d, Y', strtotime($policy->renewal)) }}</p>
                        </div>
                        <div class="col-sm-8 col-md-4">
                            <span>{{ trans('policies.label.expiry') }}</span>
                            <p>{{ date('F d, Y', strtotime($policy->expiry)) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <span>{{ trans('policies.label.payer') }}</span>
                            <p>{{ $policy->payer }}</p>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <span>{{ trans('policies.label.premium') }}</span>
                            <p>
                                {{ $policy->currency_symbol }}{{ $policy->premium }}
                            </p>
                        </div>
                        <div class="col-sm-5 col-md-5">
                                @if ($policy->premium <= $policy->paid && $policy->paid > 0)
                                <span class="ui {{ $policy->statusClass }} large tag label"> <strong>{{ trans('policies.label.paid_in_full') }}</strong> </span>
                                @elseif ($policy->due > 0 && $policy->active)
                                <span class="ui {{ $policy->statusClass }} large tag label"> <strong>{{ trans('policies.label.due') }} -</strong> {{ $policy->currency_symbol }}{{ $policy->due }} </span>
                                @elseif ($policy->due > 0 && !$policy->active)
                                <span class="ui {{ $policy->statusClass }} large tag label"> <strong>{{ trans('policies.label.expired_and_due') }} -</strong> {{ $policy->currency_symbol }}{{ $policy->due }} </span>
                                @elseif ($policy->premium == $policy->paid && $policy->paid === 0)
                                <span class="ui yellow large tag label"> <strong>{{ trans('policies.label.free') }}</strong> </span>
                                @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span>{{ trans('policies.label.beneficiaries') }}</span>
                            <p>{{ $policy->beneficiaries }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span>{{ trans('policies.label.special_remarks') }}</span>
                            <p>{!! $policy->special_remarks !!}</p>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="row">
                        @foreach ($policy->customFields->all() as $custom_field)
                        <div class="col-md-4">
                            <span>{{ $custom_field->label }}</span>
                            <p>{{ is_object($custom_field->value) ? $custom_field->value->choice : $custom_field->value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- attachments -->
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('policies.table.title.attachments') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newAttachmentModal" data-toggle="modal">{{ trans('attachments.button.new') }}</button>
                </div>
                <table class="ui celled striped table">
                    <thead>
                        <tr>
                            <th> {{ trans('policies.table.header.file') }} </th>
                            <th> {{ trans('policies.table.header.date') }} </th>
                            <th> {{ trans('policies.table.header.uploader') }} </th>
                            <th class="center aligned"> {{ trans('policies.table.header.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policy->attachments as $attachment)
                        <tr>
                            <td>
                                <i class="file {{ array(
                                    'bmp'   => 'image',
                                    'doc'   => 'word',
                                    'docx'  => 'word',
                                    'gif'   => 'image',
                                    'jpeg'  => 'image',
                                    'jpg'   => 'image',
                                    'png'   => 'image',
                                    'ppt'   => 'powerpoint',
                                    'pptx'  => 'powerpoint',
                                    'pdf'   => 'pdf',
                                    'svg'   => 'image',
                                    'xls'   => 'excel',
                                    'xlsx'  => 'excel'
                                )[pathinfo(storage_path('app/attachments/' . $attachment->filename), PATHINFO_EXTENSION)] }} outline icon"></i> {{ $attachment->name }}
                            </td>
                            <td>{{ date('F d, Y', strtotime($attachment->created_at)) }}</td>
                            <td>{{ $attachment->uploader->first_name . ' ' . $attachment->uploader->last_name }}</td>
                            <td class="center aligned">
                                <a class="ui tiny grey label" href="{{ url('uploads/attachments/' . $attachment->filename) }}" target="_blank"> {{ trans('policies.table.data.action.view') }} </a>
                                <form action="{{ action('AttachmentController@delete', array($attachment->id)) }}" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="delete label tiny red ui" style="cursor:pointer;" type="submit">{{ trans('policies.table.data.action.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="center aligned" colspan="4">{{ trans('policies.table.message.empty.attachments', array(
                                'policy' => $policy->ref_no
                            )) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- end attachments -->

            <!-- payments -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('policies.table.title.payments') }}</h3>
                    @if($policy->due > 0)
                        @yield('payments_button')
                    @endif
                </div>
                <table class="ui striped table">
                    <thead>
                        <tr>
                            <th>{{ trans('policies.table.header.number') }}</th>
                            <th>{{ trans('policies.table.header.amount') }}</th>
                            <th>{{ trans('policies.table.header.date') }}</th>
                            <th>{{ trans('policies.table.header.method') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policy->payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $policy->client->currency_symbol }}{{ $payment->amount }}</td>
                            <td>{{ date('F d, Y', strtotime($payment->date)) }}</td>
                            <td>
                                <i class="{{ array(
                                    'card'      => 'credit card alternative',
                                    'cash'      => 'money',
                                    'paypal'    => 'paypal card'
                                )[$payment->method] }} icon"></i> {{ array(
                                    'card'      => trans('clients.table.data.method.card'),
                                    'cash'      => trans('clients.table.data.method.cash'),
                                    'paypal'    => trans('clients.table.data.method.paypal')
                                )[$payment->method] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="center aligned" colspan="4">{{ trans('policies.table.message.empty.payments', array(
                                'name'  => $policy->client->first_name
                            )) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- end payments -->
        </div>
        <!-- end half page content -->
@endsection

@section('modals')
    <!-- new attachment modal -->
    <div class="ui tiny modal" id="newAttachmentModal">
        <div class="header">{{ trans('attachments.modal.header.new') }}</div>
        <div class="content">
            <form action="{{ action('AttachmentController@add') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="attachee" value="{{ $policy->id }}"/>
                <input type="hidden" name="attachee_type" value="policy"/>
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('attachments.input.label.name') }}</label>
                        <input type="text" name="name" placeholder="{{ trans('attachments.input.placeholder.name') }}" required value="{{ old('name') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('attachments.input.label.attachment') }}</label>
                        <input type="file" accept="image/*, application/pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" class="file-upload" data-allowed-file-extensions="bmp doc docx gif jpeg jpg pdf png ppt pptx svg xls xlsx" name="attachment" required/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('attachments.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('attachments.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('attachments.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
    @yield('policy_modals')
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/datepicker/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                if ($(window).width() > 992) {
                    getVisible();
                }else{
                    $('div.right-bar-profile div.scrollbar').removeAttr("style");
                }

                $(window).resize(function(){
                    if ($(window).width() > 992) {
                        getVisible();
                    }else {
                        $('.scrollbar').removeAttr("style");
                    }
                });

                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initModal('div#newAttachmentModal', true);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form button.delete', {
                    confirmButtonText: '{{ trans('attachments.swal.warning.delete.confirm') }}',
                    text: '{{ trans('attachments.swal.warning.delete.text') }}',
                    title: '{{ trans('attachments.swal.warning.delete.title') }}'
                });
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('div.required select, div.required div.dropdown input[type="hidden"]');
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
