@extends('global.app')

@section('title', trans('staff.title.one'))

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
            <!-- staff profile -->
            <div class="user-profile m-b-15">
                <div class="circular ui icon button top right pointing dropdown edit-profile-btn" data-inverted="" data-tooltip="{{ trans('staff.menu.header.tooltip') }}" data-position="left center">
                    <i class="ion-android-more-horizontal icon"></i>
                    <div class="menu">
                        <div class="header">{{ trans('staff.menu.header.text') }}</div>
                        <div class="divider"></div>
                        <div class="item">
                            <a href="#" data-target="#editStaffModal" data-toggle="modal"><i class="write icon"></i> {{ trans('staff.menu.item.edit_profile') }}</a>
                        </div>
                        <div class="item">
                            <a href="{{ action('InboxController@getAll', array(
                                'chatee'  => $staff->id
                            )) }}"><i class="comments icon"></i> {{ trans('staff.menu.item.chat') }}</a>
                        </div>
                        <form action="{{ action('StaffController@delete', array($staff->id)) }}" class="item negative" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a href="#" class="delete"><i class="trash icon"></i>{{ trans('staff.menu.item.delete') }}</a>
                        </form>
                    </div>
                </div>
                @if ($staff->profile_image_filename === 'default-profile.jpg')
                <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($staff->first_name[0] . (isset($staff->last_name) ? $staff->last_name[0] : '')) }}</div>
                @else
                <img src="{{ asset('uploads/images/users/' . $staff->profile_image_filename) }}" alt="{{ $staff->first_name . ' ' . $staff->last_name }}">
                @endif
                <h3>{{ $staff->first_name . ' ' . $staff->last_name }}</h3>
                <span>
                    @if ($staff->status)
                    <i class="ion-ios-circle-filled text-success"></i> {{ trans('staff.status.active') }}
                    @else
                    <i class="ion-ios-circle-filled text-danger"></i> {{ trans('staff.status.inactive') }}
                    @endif
                </span>
                <div class="m-t-25">
                    <button class="ui button primarish" data-target="#newTextModal" data-toggle="modal"{{ is_null($user->company->text_provider) || is_null($staff->phone) ? ' disabled' : '' }}><i class="comment icon"></i> {{ trans('staff.button.text') }} </button>
                    <button class="ui button primarish" data-target="#newEmailModal" data-toggle="modal"><i class="mail icon"></i> {{ trans('staff.button.email') }} </button>
                </div>
            </div>
            <!-- end staff profile -->
            <div class="user-more-data">
                <div class="divider m-t-0"></div>
                <div class="row text-center broker-totals">
                    <div class="col-md-6">
                        <span>{{ trans('staff.label.total_sales') }}</span>
                        <p class="text-success"><strong>{{ $staff->currency_symbol }}{{ $staff->invitees->reduce(function($count, $invitee) {
                            return $invitee->payments->sum('amount') + $count;
                        }, 0) }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <span>{{ trans('staff.label.total_commission') }}</span>
                        <p class="text-danger"><strong>{{ $staff->currency_symbol }}{{ ($staff->commisiion_rate / 100) * $staff->inviteePolicies->sum('premium') }}</strong></p>
                    </div>
                </div>
                <div class="divider m-t-0"></div>
                <div class="scrollbar">
                    <!-- staff details -->
                    <div class="user-contact">
                        <div>
                            <span>{{ trans('staff.label.email') }}</span>
                            <p>{{ $staff->email }}</p>
                        </div>
                        <div>
                            <span>{{ trans('staff.label.phone') }}</span>
                            <p>{{ $staff->phone or '(---) ---- --- ---' }}</p>
                        </div>
                        <div>
                            <span>{{ trans('staff.label.birthday') }}</span>
                            <p>{{ is_null($staff->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($staff->birthday)) }}</p>
                        </div>
                        <div>
                            <span>{{ trans('staff.label.address') }}</span>
                            <p>{{ $staff->address or '. . .' }}</p>
                        </div>
                    </div>
                    <!-- end staff details -->
                </div>
            </div>
        </div>
@endsection

@section('content')
        @parent
        <div class="half-page-content">
            @include('global.status')
            <!-- active clients -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('staff.table.title.clients') }}</h3>
                </div>
                <table class="ui striped table">
                    <thead>
                        <tr>
                            <th>{{ trans('staff.table.header.number') }}</th>
                            <th>{{ trans('staff.table.header.name') }}</th>
                            <th>{{ trans('staff.table.header.email') }}</th>
                            <th class="ui aligned center">{{ trans('staff.table.header.policies') }}</th>
                            <th>{{ trans('staff.table.header.due') }}</th>
                            <th class="ui aligned center">{{ trans('staff.table.header.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $key => $client)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $client->first_name . ' ' . $client->last_name }}</td>
                            <td class="text-ellipsis">{{ $client->email }}</td>
                            <td class="ui aligned center">
                                <div class="ui green mini label">{{ $client->policies->count() }}</div>
                            </td>
                            <td>{{ $staff->currency_symbol }}{{ $client->policies->sum('premium') - $client->payments->sum('amount') }}</td>
                            <td class="ui aligned center">
                                <a class="ui grey mini label" href="{{ action('ClientController@getOne', array($client->id)) }}"> {{ trans('staff.table.data.action.view') }} </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="6">{{ trans('staff.table.message.empty.clients', array('name' => $staff->first_name)) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- active policies -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('staff.table.title.policies') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newPolicyModal" data-toggle="modal"{{ $clients->count() > 0 ? '' : ' disabled' }}>{{ trans('policies.button.new') }}</button>
                </div>
                <table class="ui table">
                    <thead>
                        <tr>
                            <th>{{ trans('staff.table.header.ref_no') }}</th>
                            <th>{{ trans('staff.table.header.product') }}</th>
                            <th>{{ trans('staff.table.header.premium') }}</th>
                            <th>{{ trans('staff.table.header.commission') }} ({{ $staff->commission_rate + 0 }}%)</th>
                            <th class="center aligned">{{ trans('staff.table.header.status') }}</th>
                            <th class="center aligned">{{ trans('staff.table.header.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($policies as $policy)
                        <tr class="negative">
                            <td>{{ $policy->ref_no }}</td>
                            <td class="text-ellipsis">{{ $policy->product->name }}</td>
                            <td>{{ $staff->currency_symbol}}{{ $policy->premium }}</td>
                            <td>{{ $staff->currency_symbol}}{{ $policy->premium * $staff->commission_rate / 100 }}</td>
                            <td class="center aligned">
                                @if ($policy->premium <= $policy->paid && $policy->paid > 0)
                                <div class="ui green mini label"> {{ trans('staff.table.data.status.paid') }}</div>
                                @elseif ($policy->premium > $policy->paid && $policy->paid > 0)
                                <div class="ui orange mini label"> {{ trans('staff.table.data.status.partial') }}</div>
                                @elseif ($policy->premium == $policy->paid && $policy->paid === 0)
                                <div class="ui yellow mini label"> {{ trans('staff.table.data.status.free') }}</div>
                                @else ($policy->premium > 0 && $policy->paid === 0)
                                <div class="ui red mini label"> {{ trans('staff.table.data.status.unpaid') }}</div>
                                @endif
                            </td>
                            <td class="center aligned">
                                <a class="ui mini grey label" href="action('PolicyController@getOne', array($policy->id)) }}"> {{ trans('staff.table.data.action.view') }} </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="6">{{ trans('staff.table.message.empty.policies', array('name' => $staff->first_name)) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('staff.table.header.total') }}</th>
                            <th></th>
                            <th>{{ $staff->currency_symbol }}{{ $policies->sum('premium') }}</th>
                            <th>{{ $staff->currency_symbol }}{{ $policies->sum('premium') * $staff->commission_rate / 100 }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
@endsection

@section('modals')
    
    <!-- new email modal -->
    <div class="ui tiny modal" id="newEmailModal">
        <div class="header">{{ trans('communication.modal.header.email') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.send', array(
                'name'  => $staff->first_name . ' ' . $staff->last_name,
                'type'  => 'an email'
            )) }}</p>
            <form action="{{ action('EmailController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $staff->id }}"/>
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
                'name'  => $staff->first_name . ' ' . $staff->last_name,
                'type'  => 'a text message'
            )) }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.text') }}</p>
            <form action="{{ action('TextController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $staff->id }}"/>
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
        <div class="content">
            <p>{{ trans('policies.modal.instruction.new') }}</p>
            <form action="{{ action('PolicyController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.product') }}</label>
                            <select class="ui fluid selection dropdown" name="product" value="{{ old('product_id') }}">
                                <option value="">{{ trans('policies.input.placeholder.product') }}</option>
                                @forelse ($staff->company->products as $product)
                                <option{{ old('product') === $product->id ? ' selected' : '' }}" value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('policies.input.option.empty.product') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.type') }}</label>
                            <select class="ui fluid dropdown selection" name="type" value="{{ old('type') }}">
                                <option value="">{{ trans('policies.input.placeholder.type') }}</option>
                                <option{{ old('type') === 'annually' ? ' selected' : '' }} value="annually">{{ trans('policies.input.option.type.annual') }}</option>
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
                                <label for="premium" class="ui label">{{ $staff->currency_symbol }}</label>
                                <input type="number" id="premium" min="0" name="premium[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.premium') }}" required value="{{ old('premium.' . $client->id) }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $staff->currency_symbol }}</label>
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
                                <label for="premium" class="ui label">{{ $staff->currency_symbol }}</label>
                                <input type="number" disabled id="premium" min="0" placeholder="{{ trans('policies.input.placeholder.premium') }}" required/>
                            </div>
                        </div>
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $staff->currency_symbol }}</label>
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
                                        {{ trans('policies.input.option.method.cash') }}
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
    @yield('staff_modals')
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
                    $('div.right-bar-profile div.scrollbar').removeAttr("style");
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
                $insura.helpers.initModal('div#editStaffModal', false);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form a.delete', {
                    confirmButtonText: '{{ trans('staff.swal.warning.delete.confirm') }}',
                    text: '{{ trans('staff.swal.warning.delete.text') }}',
                    title: '{{ trans('staff.swal.warning.delete.title') }}'
                });
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select, form div.required div.dropdown input[type="hidden"]');
                
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
