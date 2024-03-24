@extends('global.app')

@section('title', trans('communication.title.texts'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('profile_bar')
        <div class="ui segment white right-bar-profile">
            <!-- client profile -->
            <div class="user-profile m-b-15">
                @if ($recipient->profile_image_filename === 'default-profile.jpg')
                <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($recipient->first_name[0] . $recipient->last_name[0]) }}</div>
                @else
                <img src="{{ asset('uploads/images/users/' . $recipient->profile_image_filename) }}" alt="{{ $recipient->first_name . ' ' . $recipient->last_name }}"/>
                @endif
                <h3>{{ $recipient->first_name . ' ' . $recipient->last_name }}</h3>
                <span>
                    @if ($recipient->status)
                    <i class="ion-ios-circle-filled text-success"></i> {{ trans('communication.status.active', array(
                        'role'  => studly_case($recipient->role),
                    )) }}
                    @else
                    <i class="ion-ios-circle-filled text-danger"></i> {{ trans('communication.status.inactive', array(
                        'role'  => studly_case($recipient->role),
                    )) }}
                    @endif
                </span>
                <div class="m-t-25">
                    @if(in_array($user->role, array('super', 'admin')) && in_array($recipient->role, array('broker', 'client', 'staff')))
                    <a class="ui button positive" href="{{ action(studly_case($recipient->role) . 'Controller@getOne', array($recipient->id)) }}"><i class="user outline icon"></i> {{ trans('communication.button.profile') }} </a>
                    @endif
                </div>
            </div>
            <div class="scrollbar">
                <div class="user-more-data">
                    <div class="divider m-t-0"></div>
                    <!-- client details -->
                    <div class="user-contact">
                        <div>
                            <span>{{ trans('communication.label.email') }}</span>
                            <p>{{ $recipient->email }}</p>
                        </div>
                        <div>
                            <span>{{ trans('communication.label.phone') }}</span>
                            <p>{{ $recipient->phone or '(---) ---- --- ---' }}</p>
                        </div>
                        <div>
                            <span>{{ trans('communication.label.birthday') }}</span>
                            <p>{{ is_null($recipient->birthday) ? '---------- --, ----' : date('jS F Y', strtotime($recipient->birthday)) }}</p>
                        </div>
                        <div>
                            <span>{{ trans('communication.label.address') }}</span>
                            <p>{{ $recipient->address or '. . .' }}</p>
                        </div>
                    </div>
                    <!-- end client details -->
                </div>
            </div>
        </div>
@endsection

@section('content')
        @parent
        <div class="half-page-content">
            @include('global.status')
            <!-- Policy details -->
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('communication.table.title.texts', array(
                            'name'  => $recipient->first_name
                        )) }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newTextModal" data-toggle="modal"{{ is_null($user->company->text_provider) || is_null($recipient->phone) ? ' disabled' : '' }}>{{ trans('communication.button.text') }}</button>
                </div>
                <div class="sms-list">
                    @forelse ($texts as $text)
                    <div class="sms-item text-{{ $text->status ? 'success' : 'danger' }}">
                        <h4>{{ trans('communication.table.header.from') }}: <span>{{ $text->sender->phone }} - {{ $text->sender->first_name }}</span></h4>
                        <form action="{{ action('TextController@delete', array($text->id)) }}" class="delete-sms" data-tooltip="{{ trans('communication.table.data.action.delete') }}" data-position="left center" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <i class="ion-ios-close-empty icon"></i>
                        </form>
                        <p>
                            {{ $text->message }}<br/>
                            <span>{{ date('F d, Y H:i', strtotime($text->created_at)) }}</span>
                        </p>
                    </div>
                    @empty
                    <div class="sms-item">
                        <p class="text-center">{{ trans('communication.table.message.empty') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
@endsection

@section('modals')
    <!-- new text modal -->
    <div class="ui tiny modal" id="newTextModal">
        <div class="header">{{ trans('communication.modal.header.text') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.send', array(
                'name'  => $recipient->first_name . ' ' . $recipient->last_name,
                'type'  => 'a text message'
            )) }}</p>
            <form action="{{ action('TextController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $recipient->id }}"/>
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
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initModal('div.modal', true);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form i.ion-ios-close-empty', {
                    confirmButtonText: '{{ trans('communication.swal.warning.delete.confirm') }}',
                    text: '{{ trans('communication.swal.warning.delete.text', array(
                        'type'  => 'text / SMS'
                    )) }}',
                    title: '{{ trans('communication.swal.warning.delete.title') }}'
                });
                $insura.helpers.listenForChats();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
