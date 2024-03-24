@extends('global.app')

@section('title', trans('brokers.title.one'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/libs/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('extra_stylesheets')
    <link href="{{ asset('assets/css/split-page.css') }}" rel="stylesheet"/>
@endsection

@section('profile_bar')
        <div class="ui segment white right-bar-profile">
            <!-- broker profile -->
            <div class="user-profile m-b-15">
                <div class="circular ui icon button top right pointing dropdown edit-profile-btn" data-inverted="" data-tooltip="{{ trans('brokers.menu.header.tooltip') }}" data-position="left center">
                    <i class="ion-android-more-horizontal icon"></i>
                    <div class="menu">
                        <div class="header">{{ trans('brokers.menu.header.text') }}</div>
                        <div class="divider"></div>
                        <div class="item">
                            <a href="#" data-target="#editBrokerModal" data-toggle="modal"><i class="write icon"></i> {{ trans('brokers.menu.item.edit_profile') }}</a>
                        </div>
                        <div class="item">
                            <a href="{{ action('InboxController@getAll', array(
                                'chatee'  => $broker->id
                            )) }}"><i class="comments icon"></i> {{ trans('brokers.menu.item.chat') }}</a>
                        </div>
                        <form action="{{ action('BrokerController@delete', array($broker->id)) }}" class="item negative" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a href="#" class="delete"><i class="trash icon"></i>{{ trans('brokers.menu.item.delete') }}</a>
                        </form>
                    </div>
                </div>
                @if ($broker->profile_image_filename === 'default-profile.jpg')
                <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($broker->first_name[0] . (isset($broker->last_name) ? $broker->last_name[0] : '')) }}</div>
                @else
                <img src="{{ asset('uploads/images/users/' . $broker->profile_image_filename) }}" alt="{{ $broker->first_name . ' ' . $broker->last_name }}">
                @endif
                <h3>{{ $broker->first_name . ' ' . $broker->last_name }}</h3>
                <span>
                    @if ($broker->status)
                    <i class="ion-ios-circle-filled text-success"></i> {{ trans('brokers.status.active') }}
                    @else
                    <i class="ion-ios-circle-filled text-danger"></i> {{ trans('brokers.status.inactive') }}
                    @endif
                </span>
                <div class="m-t-25">
                    <button class="ui button primarish" data-target="#newTextModal" data-toggle="modal"{{ is_null($user->company->text_provider) || is_null($broker->phone) ? ' disabled' : '' }}><i class="comment icon"></i> {{ trans('brokers.button.text') }} </button>
                    <button class="ui button primarish" data-target="#newEmailModal" data-toggle="modal"><i class="mail icon"></i> {{ trans('brokers.button.email') }} </button>
                </div>
            </div>
            <!-- end broker profile -->
            <div class="user-more-data">
                <div class="divider m-t-0"></div>
                <div class="row text-center broker-totals">
                    <div class="col-md-6">
                        <span>{{ trans('brokers.label.total_sales') }}</span>
                        @if($broker->inviteePolicies->sum('premium') > $broker->inviteePayments->sum('amount;'))
                        <p class="text-danger">
                        @else
                        <p class="text-success">
                        @endif
                            <strong>{{ $broker->currency_symbol }}{{ $broker->inviteePolicies->sum('premium') }}</strong>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <span>{{ trans('brokers.label.total_commission') }}</span>
                        @if($broker->inviteePolicies->sum('premium') > $broker->inviteePayments->sum('amount;'))
                        <p class="text-danger">
                        @else
                        <p class="text-success">
                        @endif
                            <strong>{{ $broker->currency_symbol }}{{ ($broker->commission_rate / 100) * $broker->inviteePolicies->sum('premium') }}</strong>
                        </p>
                    </div>
                </div>
                <div class="divider m-t-0"></div>
                <!-- broker details -->
                <div class="scrollbar">
                    <div class="user-contact">
                        <div>
                            <span>{{ trans('brokers.label.email') }}</span>
                            <p>{{ $broker->email }}</p>
                        </div>
                        <div>
                            <span>{{ trans('brokers.label.phone') }}</span>
                            <p>{{ $broker->phone or '(---) ---- --- ---' }}</p>
                        </div>
                        <div>
                            <span>{{ trans('brokers.label.birthday') }}</span>
                            <p>{{ is_null($broker->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($broker->birthday)) }}</p>
                        </div>
                        <div>
                            <span>{{ trans('brokers.label.address') }}</span>
                            <p>{{ $broker->address or '. . .' }}</p>
                        </div>
                    </div>
                </div>
                <!-- end broker details -->
            </div>
        </div>
@endsection

@section('content')
        @parent
        <div class="half-page-content">
            @include('global.status')
            <!-- clients -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('brokers.table.title.clients') }}</h3>
                </div>
                <table class="ui striped table">
                    <thead>
                        <tr>
                            <th>{{ trans('brokers.table.header.number') }}</th>
                            <th>{{ trans('brokers.table.header.name') }}</th>
                            <th>{{ trans('brokers.table.header.email') }}</th>
                            <th class="ui aligned center">{{ trans('brokers.table.header.policies') }}</th>
                            <th>{{ trans('brokers.table.header.due') }}</th>
                            <th class="ui aligned center">{{ trans('brokers.table.header.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $key => $client)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $client->first_name . ' ' . $client->last_name }}</td>
                            <td class="text-ellipsis">{{ $client->email }}</td>
                            <td class="ui aligned center">{{ $client->policies->count() }}</td>
                            <td>{{ $broker->currency_symbol }}{{ $client->policies->sum('premium') - $client->payments->sum('amount') }}</td>
                            <td class="ui aligned center">
                                <a class="ui grey mini label" href="{{ action('ClientController@getOne', array($client->id)) }}"> {{ trans('brokers.table.data.action.view') }} </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="6">{{ $broker->first_name }}{{ trans('brokers.table.message.empty.clients') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- end clients -->

            <!-- policies -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('brokers.table.title.policies') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newPolicyModal" data-toggle="modal"{{ $clients->count() > 0 ? '' : ' disabled' }}>{{ trans('policies.button.new') }}</button>
                </div>
                <table class="ui table">
                    <thead>
                        <tr>
                            <th>{{ trans('brokers.table.header.ref_no') }}</th>
                            <th>{{ trans('brokers.table.header.product') }}</th>
                            <th>{{ trans('brokers.table.header.premium') }}</th>
                            <th>{{ trans('brokers.table.header.commission') }} ({{ $broker->commission_rate }}%)</th>
                            <th class="center aligned">{{ trans('brokers.table.header.status') }}</th>
                            <th class="center aligned">{{ trans('brokers.table.header.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($policies as $policy)
                        <tr class="{{ $policy->statusClass }}">
                            <td>{{ $policy->ref_no }}</td>
                            <td class="text-ellipsis">{{ $policy->product->name }}</td>
                            <td>{{ $broker->currency_symbol }}{{ $policy->premium + 0 }}</td>
                            <td>{{ $broker->currency_symbol }}{{ $policy->premium * $broker->commission_rate / 100 }}</td>
                            <td class="center aligned">
                                @if ($policy->premium <= $policy->paid && $policy->paid > 0)
                                <div class="ui green mini label"> {{ trans('brokers.table.data.status.paid') }}</div>
                                @elseif ($policy->premium > $policy->paid && $policy->paid > 0)
                                <div class="ui orange mini label"> {{ trans('brokers.table.data.status.partial') }}</div>
                                @elseif ($policy->premium == $policy->paid && $policy->paid === 0)
                                <div class="ui yellow mini label"> {{ trans('brokers.table.data.status.free') }}</div>
                                @else ($policy->premium > 0 && $policy->paid === 0)
                                <div class="ui red mini label"> {{ trans('brokers.table.data.status.unpaid') }}</div>
                                @endif
                            </td>
                            <td class="center aligned">
                                <a class="ui mini grey label" href="{{ action('PolicyController@getOne', array($policy->id)) }}"> {{ trans('brokers.table.data.action.view') }} </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="7">{{ $broker->first_name }}{{ trans('brokers.table.message.empty.policies') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('brokers.table.header.total') }}</th>
                            <th></th>
                            <th>{{ $broker->currency_symbol }}{{ $policies->sum('premium') }}</th>
                            <th>{{ $broker->currency_symbol }}{{ $policies->sum('premium') * $broker->commission_rate / 100 }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- end policies -->
        </div>
@endsection

@section('modals')
    <!-- new email modal -->
    <div class="ui tiny modal" id="newEmailModal">
        <div class="header">{{ trans('communication.modal.header.email') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.send', array(
                'name'  => $broker->first_name . ' ' . $broker->last_name,
                'type'  => 'an email'
            )) }}</p>
            <form action="{{ action('EmailController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $broker->id }}"/>
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('communication.input.label.subject') }}</label>
                        <input type="text" name="subject" placeholder="{{ trans('communication.input.placeholder.subject') }}" required value="{{ old('subject') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('communication.input.label.message') }}</label>
                        <textarea rows="5" name="message" placeholder="{{ trans('communication.input.placeholder.message') }}" required>{{ session('text') ? '' : old('message') }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('communication.modal.button.cancel') }}</button>
                <div class="or" data-text="{{ trans('communication.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('communication.modal.button.confirm') }}</button>
            </div>
        </div>
    </div>

    <!-- new text modal -->
    <div class="ui tiny modal" id="newTextModal">
        <div class="header">{{ trans('communication.modal.header.send', array(
                'name'  => $broker->first_name . ' ' . $broker->last_name,
                'type'  => 'a text message'
            )) }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.text') }}</p>
            <form action="{{ action('TextController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $broker->id }}"/>
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('communication.input.label.message') }}</label>
                        <textarea rows="5" name="message" placeholder="{{ trans('communication.input.placeholder.message') }}" required>{{ session('text') ? old('message') : '' }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('communication.modal.button.cancel') }}</button>
                <div class="or" data-text="{{ trans('communication.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('communication.modal.button.confirm') }}</button>
            </div>
        </div>
    </div>

    <!-- new policy modal -->
    <div class="ui tiny modal" id="newPolicyModal">
        <div class="header">{{ trans('policies.modal.header.new') }}</div>
        <div class="scrolling content">
            <p>{{ trans('policies.modal.instruction.new') }} {{ trans('brokers.modal.instruction.policy', array(
                'name'  => $broker->first_name
            )) }}</p>
            <form action="{{ action('PolicyController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.product') }}</label>
                            <select class="ui fluid search dropdown" name="product" required value="{{ old('product') }}">
                                <option value="">{{ trans('policies.input.placeholder.product') }}</option>
                                @forelse ($broker->company->products as $product)
                                <option{{ old('product') === $product->id ? ' selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('policies.input.option.empty.product') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.type') }}</label>
                            <select class="ui fluid dropdown selection" name="type" required value="{{ old('type') }}">
                                <option value="">{{ trans('policies.input.placeholder.type') }}</option>
                                <option{{ old('type') === 'annually' ? ' selected' : '' }} value="annually">{{ trans('policies.input.option.type.annually') }}</option>
                                <option{{ old('type') === 'monthly' ? ' selected' : '' }} value="monthly">{{ trans('policies.input.option.type.monthly') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.expiry') }}</label>
                            <div class="ui labeled input">
                                <label for="expiry" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="expiry" class="datepicker" name="expiry" placeholder="{{ trans('policies.input.placeholder.expiry') }}" required value="{{ old('expiry') }}"/>
                            </div>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.renewal') }}</label>
                            <div class="ui labeled input">
                                <label for="renewal" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="renewal" class="datepicker" name="renewal" placeholder="{{ trans('policies.input.placeholder.renewal') }}" required value="{{ old('renewal') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.payer') }}</label>
                        <input type="text" name="payer" maxlength="64" placeholder="{{ trans('policies.input.placeholder.payer') }}" required value="{{ old('payer') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.owners') }}</label>
                        <select class="dropdown fluid ui" name="owners[]" multiple required>
                            <option value="">{{ trans('policies.input.label.owners') }}</option>
                            @forelse ($clients as $client)
                            <option{{ collect(old('owners'))->contains($client->id) ? ' selected' : '' }} value="{{ $client->id }}">{{ $client->first_name . ' ' . $client->last_name }}</option>
                            @empty
                            <option disabled value="">{{ trans('policies.input.option.empty.owners') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="divider"></div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.beneficiaries') }}</label>
                        <input type="text" name="beneficiaries" placeholder="{{ trans('policies.input.placeholder.beneficiaries') }}" required value="{{ old('beneficiaries') }}"/>
                    </div>
                    <div class="field">
                        <label>{{ trans('policies.input.label.special_remarks') }}</label>
                        <textarea name="special_remarks" placeholder="{{ trans('policies.input.placeholder.special_remarks') }}" rows="4">{{ old('special_remarks') }}</textarea>
                    </div>
                    @forelse($clients as $client)
                    <h5 class="client client{{ $client->id }} ui dividing header" style="display:none;">{{ trans('policies.input.label.payments', array(
                        'name'  => $client->first_name. ' ' .$client->last_name
                    )) }}</h5>
                    <div class="client client{{ $client->id }} two fields" style="display:none;">
                        <div class="field required" data-input-name="premium">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $broker->currency_symbol }}</label>
                                <input type="number" id="premium" min="0" name="premium[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.premium') }}" required value="{{ old('premium.' . $client->id) }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $broker->currency_symbol }}</label>
                                <input type="number" id="amount" name="amount[{{ $client->id }}]" min="0" placeholder="{{ trans('policies.input.placeholder.amount') }}" value="{{ old('amount.' . $client->id) }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="client client{{ $client->id }} two fields" style="display:none;">
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_date') }}</label>
                            <input type="text" class="datepicker" name="date[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.payment_date') }}" value="{{ old('date.' . $client->id) }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_method') }}</label>
                            <div class="ui selection dropdown required">
                                <input type="hidden" name="method[{{ $client->id }}]" value="{{ old('method.' . $client->id) }}"/>
                                <div class="default text">{{ trans('policies.input.placeholder.payment_method') }}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item{{ old('method.' . $client->id) === 'card' ? ' selected' : null }}" data-value="card">
                                        <i class="credit card alternative icon"></i>
                                        {{ trans('policies.input.option.method.card') }}
                                    </div>
                                    <div class="item{{ old('method.' . $client->id) === 'cash' ? ' selected' : null }}" data-value="cash">
                                        <i class="money icon"></i>
                                        {{ trans('policies.input.option.method.cash') }}
                                    </div>
                                    <div class="item{{ old('method.' . $client->id) === 'paypal' ? ' selected' : null }}" data-value="paypal">
                                        <i class="paypal card icon"></i>
                                        {{ trans('policies.input.option.method.paypal') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <h5 class="ui dividing header disabled">{{ trans('policies.input.label.payments', array(
                        'name'  => '?'
                    )) }}</h5>
                    <div class="two fields">
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $broker->currency_symbol }}</label>
                                <input type="number" disabled id="premium" min="0" placeholder="{{ trans('policies.input.placeholder.premium') }}" required/>
                            </div>
                        </div>
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $broker->currency_symbol }}</label>
                                <input type="number" disabled id="amount" min="0" placeholder="{{ trans('policies.input.placeholder.amount') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.payment_date') }}</label>
                            <input type="text" class="datepicker" disabled placeholder="{{ trans('policies.input.placeholder.payment_date') }}"/>
                        </div>
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.payment_method') }}</label>
                            <div class="ui selection disabled dropdown">
                                <input type="hidden" value="{{ old('method') }}"/>
                                <div class="default text">{{ trans('policies.input.placeholder.payment_method') }}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item" data-value="card">
                                        <i class="credit card alternative icon"></i>
                                        {{ trans('policies.input.option.method.card') }}
                                    </div>
                                    <div class="item" data-value="cash">
                                        <i class="money icon"></i>
                                        {{ trans('policies.input.option.method.cash') }}
                                    </div>
                                    <div class="item" data-value="paypal">
                                        <i class="paypal card icon"></i>
                                        {{ trans('policies.input.option.method.paypal') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('policies.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('policies.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('policies.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
    @yield('broker_modals')
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/datepicker/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/intl-tel-input/js/intlTelInput.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {                
                if ($(window).width() > 992) {
                    getVisible();
                }else{
                    $('.scrollbar').removeAttr("style");
                }

                $(window).resize(function(){
                    if ($(window).width() > 992) {
                        getVisible();
                    }else{
                        $('div.right-bar-profile div.scrollbar').removeAttr("style");
                    }
                });

                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initModal('div#newEmailModal, div#newPolicyModal, div#newTextModal', true); 
                $insura.helpers.initModal('div#editBrokerModal', false);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form a.delete', {
                    confirmButtonText: '{{ trans('brokers.swal.warning.delete.confirm') }}',
                    text: '{{ trans('brokers.swal.warning.delete.text') }}',
                    title: '{{ trans('brokers.swal.warning.delete.title') }}'
                });
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select, form div.required div.dropdown input[type="hidden"]');

                $('div#newPolicyModal input[name^="premium["]').change(function() {
                    var input = $(this);
                    input.closest('div.fields').find('input[name^="amount["]').attr('max', input.val());
                }).change();
                $('select[name="owners[]"]').change(function() {
                    $('h5.client').fadeOut(200);
                    $('div.client').fadeOut(200).find('input').attr('required', false);
                    $.each($(this).val(), function(i, value) {
                        $('h5.client' + value).fadeIn(200);
                        $('div.client' + value).fadeIn(200).find('input').attr('required', true);
                    });
                }).change();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
