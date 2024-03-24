@extends('global.app')

@section('title', trans('clients.title.one'))

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
        <!-- client profile -->
        <div class="ui segment white right-bar-profile">
            <div class="user-profile m-b-15">
                <div class="circular ui icon button top right pointing dropdown edit-profile-btn" data-inverted="" data-tooltip="{{ trans('clients.menu.header.tooltip') }}" data-position="left center">
                    <i class="icon ion-more"></i>
                    <div class="menu">
                        <div class="header">{{ trans('clients.menu.header.text') }}</div>
                        <div class="divider"></div>
                        <div class="item">
                            <a href="#" data-target="#editClientModal" data-toggle="modal"><i class="write icon"></i> {{ trans('clients.menu.item.edit_profile') }}</a>
                        </div>
                        <div class="item">
                            <a href="{{ action('InboxController@getAll', array(
                                'chatee'  => $client->id
                            )) }}"><i class="comments icon"></i> {{ trans('clients.menu.item.chat') }}</a>
                        </div>
                        <form action="{{ action('ClientController@delete', array($client->id)) }}" class="item negative" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a href="#" class="delete"><i class="trash icon"></i>{{ trans('clients.menu.item.delete') }}</a>
                        </form>
                    </div>
                </div>
                @if ($client->profile_image_filename === 'default-profile.jpg')
                <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($client->first_name[0] . (isset($client->last_name) ? $client->last_name[0] : '')) }}</div>
                @else
                <img src="{{ asset('uploads/images/users/' . $client->profile_image_filename) }}" alt="{{ $client->first_name . ' ' . $client->last_name }}"/>
                @endif
                <h3>{{ $client->first_name . ' ' . $client->last_name }}</h3>
                <span>
                    @if ($client->status)
                    <i class="ion-ios-circle-filled text-success"></i> {{ trans('clients.status.active') }}
                    @else
                    <i class="ion-ios-circle-filled text-danger"></i> {{ trans('clients.status.inactive') }}
                    @endif
                </span>
                <div class="m-t-25">
                    <button class="ui button primarish" data-target="#newTextModal" data-toggle="modal"{{ is_null($user->company->text_provider) || is_null($client->phone) ? ' disabled' : '' }}><i class="comment icon"></i> {{ trans('clients.button.text') }} </button>
                    <button class="ui button primarish" data-target="#newEmailModal" data-toggle="modal"><i class="mail icon"></i> {{ trans('clients.button.email') }} </button>
                </div>
            </div>
            <div class="scrollbar">
                <div class="user-more-data">
                    <div class="divider m-t-0"></div>
                    <!-- client details -->
                    <div class="user-contact">
                        <div>
                            <span>{{ trans('clients.label.email') }}</span>
                            <p>{{ $client->email }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.phone') }}</span>
                            <p>{{ $client->phone or '(---) ---- --- ---' }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.birthday') }}</span>
                            <p>{{ is_null($client->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($client->birthday)) }}</p>
                        </div>
                        <div>
                            <span>{{ trans('clients.label.address') }}</span>
                            <p>{{ $client->address or '. . .' }}</p>
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
            <!-- Client details -->
            <div class="ui segment white fs-16 client-details">
                <div class="segment-header">
                    <h3>{{ trans('clients.label.details') }}</h3>
                    <div class="ui right floated successish button top m-w-140 right pointing dropdown" data-inverted="" data-tooltip="{{ trans('clients.menu.header.tooltip') }}" data-position="left center">
                        <i class="ion-more icon"></i>
                        <span>{{ trans('clients.menu.header.button') }}</span>
                        <div class="menu">
                            <div class="header">
                                {{ trans('clients.menu.header.text') }}
                            </div>
                            <div class="divider"></div>
                            <div class="item">
                                <a href="#" data-target="#editClientModal" data-toggle="modal">
                                    <i class="write icon"></i> {{ trans('clients.menu.item.edit_profile') }} 
                                </a>
                            </div>
                            <div class="item">
                                <a href="{{ action('InboxController@getAll', array(
                                    'chatee'  => $client->id
                                )) }}"><i class="comments icon"></i> {{ trans('clients.menu.item.chat') }}</a>
                            </div>
                            <form action="{{ action('ClientController@delete', array($client->id)) }}" class="item negative" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="#" class="delete">
                                    <i class="trash icon"></i> {{ trans('clients.menu.item.delete') }} 
                                </a>
                            </form>
                            <div class="item">
                                <a href="#" data-target="#newEmailModal" data-toggle="modal">
                                    <i class="mail icon"></i> {{ trans('clients.menu.item.email') }} 
                                </a>
                            </div>
                            <div class="item{{ is_null($user->company->text_provider) || is_null($client->phone) ? ' disabled' : '' }}">
                                <a href="#" data-target="#newTextModal" data-toggle="modal">
                                    <i class="comment icon"></i> {{ trans('clients.menu.item.text') }} 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="policy-details">
                    <div class="row">
                        <div class="col-sm-3 col-md-4">
                            <span>{{ trans('clients.label.name') }}</span>
                            <p>{{ $client->first_name . ' ' . $client->last_name }}</p>
                        </div>
                        <div class="col-sm-5 col-md-4">
                            <span>{{ trans('clients.label.policies') }}</span>
                            <p>{{ $client->policies->count() }}</p>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            @if ($client->policies->sum('premium') <= $client->policies->sum('paid') && $client->policies->sum('paid') > 0)
                            <span class="ui green large tag label"> <strong>{{ trans('policies.label.paid_in_full') }}</strong> </span>
                            @elseif ($client->policies->sum('due') > 0 && $client->policies->where('statusClass', 'warning')->count() > ($client->policies->count() / 3))
                            <span class="ui orange large tag label"> <strong>{{ trans('policies.label.due') }} -</strong> {{ $client->currency_symbol }}{{ $client->policies->sum('due') }} </span>
                            @elseif ($client->policies->sum('due') > 0 && $client->policies->where('statusClass', 'negative')->count() > ($client->policies->count() / 3))
                            <span class="ui red large tag label"> <strong>{{ trans('policies.label.due') }} -</strong> {{ $client->currency_symbol }}{{ $client->policies->sum('due') }} </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <span>{{ trans('clients.label.email') }}</span>
                            <p>{{ $client->email }}</p>
                        </div>
                        <div class="col-sm-8 col-md-4">
                            <span>{{ trans('clients.label.birthday') }}</span>
                            <p>{{ is_null($client->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($client->birthday)) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <span>{{ trans('clients.label.phone') }}</span>
                            <p>{{ $client->phone or '(---) ---- --- ---' }}</p>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <span>{{ trans('clients.label.address') }}</span>
                            <p>{{ $client->address or '. . .' }}</p>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="row">
                        @foreach ($client->customFields->all() as $custom_field)
                        <div class="col-md-4">
                            <span>{{ $custom_field->label }}</span>
                            <p>{{ is_object(json_decode($custom_field->value)) ? json_decode($custom_field->value)->choice : $custom_field->value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- end client details -->

            <!-- notes -->
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('clients.table.title.notes', array(
                        'name'  => $client->first_name
                    )) }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newNoteModal" data-toggle="modal">{{ trans('clients.button.notes') }}</button>
                </div>
                <table class="ui celled striped table">
                    <thead>
                        <tr>
                            <th> {{ trans('clients.table.header.from') }} </th>
                            <th> {{ trans('clients.table.header.message') }} </th>
                            <th> {{ trans('clients.table.header.date') }} </th>
                            <th class="center aligned"> {{ trans('clients.table.header.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($client->notes as $note)
                        <tr>
                            <td>{{ $note->writer->id === $user->id ? 'You': $note->writer->first_name }}</td>
                            <td class="text-ellipsis">{{ $note->message }}</td>
                            <td>{{ date('F d, Y', strtotime($note->created_at)) }}</td>
                            <td class="center aligned">
                                <a href="#" class="ui tiny grey label" data-target="#readNote{{ $note->id }}Modal" data-toggle="modal"> {{ trans('clients.table.data.action.read') }} </a>
                                <form action="{{ action('NoteController@delete', array($note->id)) }}" class="delete-note" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button href="#" class="ui red tiny label delete" style="cursor:pointer;" type="submit"> {{ trans('clients.table.data.action.delete') }} </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">{{ trans('clients.table.message.empty.notes', array(
                                'name' => $client->first_name
                            )) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- end notes -->

            <!-- attachments -->
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('clients.table.title.attachments') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newAttachmentModal" data-toggle="modal">{{ trans('attachments.button.new') }}</button>
                </div>
                <table class="ui celled striped table">
                    <thead>
                        <tr>
                            <th> {{ trans('clients.table.header.file') }} </th>
                            <th> {{ trans('clients.table.header.date') }} </th>
                            <th> {{ trans('clients.table.header.uploader') }} </th>
                            <th class="center aligned"> {{ trans('clients.table.header.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->attachments as $attachment)
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
                                <a href="{{ url('uploads/attachments/' . $attachment->filename) }}" class="ui tiny grey label" target="_blank"> {{ trans('clients.table.data.action.view') }} </a>
                                <form action="{{ action('AttachmentController@delete', array($attachment->id)) }}" class="delete-attachment" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="delete label tiny red ui" style="cursor:pointer;" type="submit">{{ trans('clients.table.data.action.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="center aligned" colspan="4">{{ trans('clients.table.message.empty.attachments', array(
                                'name' => $client->first_name
                            )) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- end attachments -->

            <!-- policies -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('clients.table.title.policies') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newPolicyModal" data-toggle="modal">{{ trans('clients.table.button.policies') }}</button>
                </div>
                <table class="ui table">
                    <thead>
                        <tr>
                            <th>{{ trans('clients.table.header.ref_no') }}</th>
                            <th>{{ trans('clients.table.header.product') }}</th>
                            <th>{{ trans('clients.table.header.insurer') }}</th>
                            <th>{{ trans('clients.table.header.premium') }}</th>
                            <th>{{ trans('clients.table.header.due') }}</th>
                            <th class="center aligned">{{ trans('clients.table.header.status') }}</th>
                            <th class="center aligned">{{ trans('clients.table.header.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr class="{{ $policy->statusClass }}">
                            <td>{{ $policy->ref_no }}</td>
                            <td class="text-ellipsis">{{ $policy->product->name }}</td>
                            <td>{{ $policy->product->insurer }}</td>
                            <td>{{ $client->currency_symbol}}{{ $policy->premium + 0 }}</td>
                            <td>{{ $client->currency_symbol}}{{ $policy->due }}</td>
                            <td class="center aligned">
                                @if ($policy->premium <= $policy->paid && $policy->paid > 0)
                                <div class="ui green mini label"> {{ trans('clients.table.data.status.paid') }}</div>
                                @elseif ($policy->premium > $policy->paid && $policy->paid > 0)
                                <div class="ui orange mini label"> {{ trans('clients.table.data.status.partial') }}</div>
                                @elseif ($policy->premium == $policy->paid && $policy->paid === 0)
                                <div class="ui yellow mini label"> {{ trans('clients.table.data.status.free') }}</div>
                                @else ($policy->premium > $policy->paid && $policy->paid === 0)
                                <div class="ui red mini label"> {{ trans('clients.table.data.status.unpaid') }}</div>
                                @endif
                            </td>
                            <td class="center aligned">
                                <a class="ui mini grey label" href="{{ action('PolicyController@getOne', array($policy->id)) }}"> {{ trans('clients.table.data.action.view') }} </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="center aligned" colspan="7">{{ trans('clients.table.message.empty.policies', array(
                                'name'  => $client->first_name
                            )) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('clients.table.header.total') }}</th>
                            <th></th>
                            <th></th>
                            <th>{{ $client->currency_symbol }}{{ $policies->sum('premium') }}</th>
                            <th>{{ $client->currency_symbol }}{{ $policies->sum('due') }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- end policies -->

            <!-- payments -->
            <div class="ui segment white">
                <div class="segment-header">
                    <h3>{{ trans('clients.table.title.payments') }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newPaymentModal" data-toggle="modal"{{ $policies->count() > 0 ? '' : ' disabled' }}>{{ trans('payments.button.new') }}</button>
                </div>
                <table class="ui striped table">
                    <thead>
                        <tr>
                            <th>{{ trans('clients.table.header.number') }}</th>
                            <th>{{ trans('clients.table.header.policy') }}</th>
                            <th>{{ trans('clients.table.header.amount') }}</th>
                            <th>{{ trans('clients.table.header.date') }}</th>
                            <th>{{ trans('clients.table.header.method') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $payment->policy->ref_no }}</td>
                            <td>{{ $client->currency_symbol }}{{ $payment->amount + 0 }}</td>
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
                            <td class="center aligned" colspan="5">{{ trans('clients.table.message.empty.payments', array(
                                'name'  => $client->first_name
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
    <!-- new email modal -->
    <div class="ui tiny modal" id="newEmailModal">
        <div class="header">{{ trans('communication.modal.header.email') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.send', array(
                'name'  => $client->first_name . ' ' . $client->last_name,
                'type'  => 'an email'
            )) }}</p>
            <form action="{{ action('EmailController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $client->id }}"/>
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
        <div class="header">{{ trans('communication.modal.header.text') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.send', array(
                'name'  => $client->first_name . ' ' . $client->last_name,
                'type'  => 'a text message'
            )) }}</p>
            <form action="{{ action('TextController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $client->id }}"/>
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

    <!-- new attachment modal -->
    <div class="ui tiny modal" id="newAttachmentModal">
        <div class="header">{{ trans('attachments.modal.header.new') }}</div>
        <div class="content">
            <form action="{{ action('AttachmentController@add') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="attachee" value="{{ $client->id }}"/>
                <input type="hidden" name="attachee_type" value="client"/>
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('attachments.input.label.name') }}</label>
                        <input type="text" name="name" placeholder="{{ trans('attachments.input.placeholder.name') }}" required value="{{ old('name') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('attachments.input.label.attachment') }}</label>
                        <input type="file" accept="image/*, application/pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" data-allowed-file-extensions="bmp doc docx gif jpeg jpg pdf png ppt pptx svg xls xlsx" name="attachment" class="file-upload" required/>
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
    
    <!-- new note modal -->
    <div class="ui tiny modal" id ="newNoteModal">
        <div class="header">{{ trans('notes.modal.header.new') }}</div>
        <div class="content">
            <form action="{{ action('NoteController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="client" value="{{ $client->id }}"/>
                <div class="ui form">
                    <div class="field">
                        <label>{{ trans('notes.input.label.message') }}</label>
                        <textarea name="message" placeholder="{{ trans('notes.input.placeholder.message') }}" rows="5" required rows="5">{{ old('message') }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('notes.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('notes.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('notes.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>

    <!-- new payment modal -->
    <div class="ui tiny modal" id="newPaymentModal">
        <div class="header">{{ trans('payments.modal.header.new') }}</div>
        <div class="content">
            <p>{{ trans('payments.modal.instruction.new', array(
                'name'  => $client->first_name
            )) }}</p>
            <form action="{{ action('PaymentController@add') }}"method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="client" value="{{ $client->id }}"/>
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('payments.input.label.policy') }}</label>
                            <select name="policy" class="ui fluid search dropdown" required>
                                @forelse ($policies->filter(function($policy) {
                                    return $policy->due > 0;
                                }) as $policy)
                                <option data-policy-due="{{ $policy->due }}" {{ old('policy') == $policy->id ? 'selected' : null }} value="{{ $policy->id }}">{{ $policy->ref_no }} - {{ $policy->product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('payments.input.option.empty.policy', array(
                                    'name'  => $client->first_name
                                )) }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('payments.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $client->currency_symbol }}</label>
                                <input type="number" id="amount" name="amount" placeholder="{{ trans('payments.input.placeholder.amount') }}" required value="{{ old('amount') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('payments.input.label.date') }}</label>
                            <div class="ui labeled input">
                                <label for="paymentDate" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" class="datepicker" id="paymentDate" name="date" placeholder="{{ trans('payments.input.placeholder.date') }}" required value="{{ old('date') }}"/>
                            </div>
                        </div>
                        <div class="field required">
                            <label>{{ trans('payments.input.label.method') }}</label>
                            <div class="ui selection dropdown">
                                <input type="hidden" name="method">
                                <div class="default text">{{ trans('payments.input.placeholder.method') }}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item{{ old('method') === 'card' ? ' selected' : null }}" data-value="card">
                                        <i class="credit card alternative icon"></i>
                                        {{ trans('payments.input.option.method.card') }}
                                    </div>
                                    <div class="item{{ old('method') === 'cash' ? ' selected' : null }}" data-value="cash">
                                        <i class="money icon"></i>
                                        {{ trans('payments.input.option.method.cash') }}
                                    </div>
                                    <div class="item{{ old('method') === 'paypal' ? ' selected' : null }}" data-value="paypal">
                                        <i class="paypal card icon"></i>
                                        {{ trans('payments.input.option.method.paypal') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('payments.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('payments.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('payments.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>

    <!-- new policy modal -->
    <div class="ui tiny modal" id="newPolicyModal">
        <div class="header">{{ trans('policies.modal.header.new') }}</div>
        <div class="scrolling content">
            <p>{{ trans('policies.modal.instruction.new') }} {{ trans('clients.modal.instruction.policy', array(
                'name'  => $client->first_name
            )) }}</p>
            <form action="{{ action('PolicyController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.product') }}</label>
                            <select name="product" class="ui fluid search dropdown" required value="{{ old('product') }}">
                                <option value="">{{ trans('policies.input.placeholder.product') }}</option>
                                @forelse ($client->company->products as $product)
                                <option{{ old('product') === $product->id ? ' selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('policies.input.option.empty.product') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.type') }}</label>
                            <select name="type" class="ui fluid search dropdown" required value="{{ old('type') }}">
                                <option value="">{{ trans('policies.input.placeholder.type') }}</option>
                                <option{{ old('type') === 'annually' ? ' selected' : '' }} value="annually">{{ trans('policies.input.option.type.annually') }}</option>
                                <option{{ old('type') === 'monthly' ? ' selected' : '' }} value="monthly">{{ trans('policies.input.option.type.monthly') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('policies.input.label.expiry') }}</label>
                            <div class="ui labeled input">
                                <label for="expiry" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="expiry" class="datepicker" name="expiry" placeholder="{{ trans('policies.input.placeholder.expiry') }}" required value="{{ old('expiry') }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.renewal') }}</label>
                            <div class="ui labeled input">
                                <label for="renewal" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="renewal" class="datepicker" name="renewal" placeholder="{{ trans('policies.input.placeholder.renewal') }}" required value="{{ old('renewal') }}"/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="owners[]" value="{{ $client->id }}"/>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $client->currency_symbol }}</label>
                                <input type="number" id="premium" min="0" name="premium[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.premium') }}" required value="{{ old('premium')[$client->id] }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payer') }}</label>
                            <input type="text" maxlength="64" name="payer" placeholder="{{ trans('policies.input.placeholder.payer') }}" required value="{{ old('payer') ?: $client->first_name . ' ' . $client->last_name }}"/>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.beneficiaries') }}</label>
                        <input type="text" name="beneficiaries" placeholder="{{ trans('policies.input.placeholder.beneficiaries') }}" value="{{ old('beneficiaries') }}"/>
                    </div>
                    <div class="field">
                        <label>{{ trans('policies.input.label.special_remarks') }}</label>
                        <textarea name="special_remarks" placeholder="{{ trans('policies.input.placeholder.special_remarks') }}" rows="4">{{ old('special_remarks') }}</textarea>
                    </div>
                    <h5 class="ui dividing header disabled">{{ trans('policies.input.label.payments', array(
                        'name'  => $client->first_name . ' ' . $client->last_name
                    )) }}</h5>
                    <div class="three fields">
                        <div class="field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $client->currency_symbol }}</label>
                                <input type="number" id="amount" min="0" name="amount[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.amount') }}" value="{{ old('amount')[$client->id] }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_date') }}</label>
                            <input type="text" class="datepicker" name="date[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.payment_date') }}" value="{{ old('date')[$client->id] }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_method') }}</label>
                            <div class="ui selection dropdown">
                                <input type="hidden" name="method[{{ $client->id }}]" value="{{ old('method')[$client->id] }}"/>
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

    @foreach($client->notes as $note)
    <div class="ui tiny modal" id="readNote{{ $note->id }}Modal">
        <div class="ui icon header">
            <i class="edit outline icon"></i>
            {{ date('D d F, `y', strtotime($note->created_at)) }}
        </div>
        <div class="scrolling content">
            <p>{{ $note->message }}</p>
            <p>
                <i>~{{ $note->writer->first_name . ' ' . $note->writer->last_name }}</i>
            </p>
        </div>
        <div class="actions">
            <div class="ui red cancel button">
                <i class="remove icon"></i>
                {{ trans('notes.modal.button.dismiss') }}
            </div>
        </div>
    </div>
    @endforeach

    @yield('client_modals')
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
                        $('.scrollbar').removeAttr("style");
                    }
                });

                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initModal('div#newAttachmentModal, div#newEmailModal, div#newNoteModal, div#newPaymentModal, div#newPolicyModal, div#newTextModal', true);
                $insura.helpers.initModal('div#editClientModal', false);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form a.delete', {
                    confirmButtonText: '{{ trans('clients.swal.warning.delete.confirm') }}',
                    text: '{{ trans('clients.swal.warning.delete.text') }}',
                    title: '{{ trans('clients.swal.warning.delete.title') }}'
                });
                $insura.helpers.initSwal('form.delete-attachment button.delete', {
                    confirmButtonText: '{{ trans('attachments.swal.warning.delete.confirm') }}',
                    text: '{{ trans('attachments.swal.warning.delete.text') }}',
                    title: '{{ trans('attachments.swal.warning.delete.title') }}'
                });
                $insura.helpers.initSwal('form.delete-note button.delete', {
                    confirmButtonText: '{{ trans('notes.swal.warning.delete.confirm') }}',
                    text: '{{ trans('notes.swal.warning.delete.text') }}',
                    title: '{{ trans('notes.swal.warning.delete.title') }}'
                });
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select, form div.required div.dropdown input[type="hidden"]');

                $('div#newPaymentModal select[name="policy"]').change(function() {
                    var select = $(this);
                    var policyDue = select.find('option:selected').attr('data-policy-due');
                    select.closest('div.modal').find('input[name="amount"]').attr('max', policyDue);
                }).change();
                $('div#newPolicyModal input[name^="premium["]').change(function() {
                    var input = $(this);
                    input.closest('div.modal').find('input[name^="amount["]').attr('max', input.val());
                }).change();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
