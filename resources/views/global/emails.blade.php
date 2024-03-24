@extends('global.app')

@section('title', trans('communication.title.emails'))

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
                            <p>{{ $recipient->phone or '(---) ---- --- --' }}</p>
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
            <div class="ui segment white fs-16">
                <div class="segment-header">
                    <h3>{{ trans('communication.table.title.emails', array(
                        'name'  => $recipient->first_name
                    )) }}</h3>
                    <button class="ui right floated button successish m-w-140" data-target="#newEmailModal" data-toggle="modal">{{ trans('communication.button.email') }}</button>
                </div>
                <table class="ui celled striped table">
                    <thead>
                        <tr>
                            <th> {{ trans('communication.table.header.from') }} </th>
                            <th> {{ trans('communication.table.header.subject') }} </th>
                            <th> {{ trans('communication.table.header.date') }} </th>
                            <th class="center aligned"> {{ trans('communication.table.header.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($emails as $email)
                        <tr class="{{ $email->status ? 'positive' : 'negative' }}">
                            <td>{{ $email->sender->id === $user->id ? 'You': $email->sender->first_name }}</td>
                            <td class="text-ellipsis">{{ $email->subject }}</td>
                            <td>{{ date('F d, Y', strtotime($email->created_at)) }}</td>
                            <td class="center aligned">
                                <a href="#" class="ui tiny grey label" data-target="#readEmail{{ $email->id }}Modal" data-toggle="modal"> {{ trans('communication.table.data.action.read') }} </a>
                                <form action="{{ action('EmailController@delete', array($email->id)) }}" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button href="#" class="ui red tiny label delete" style="cursor:pointer;" type="submit"> {{ trans('communication.table.data.action.delete') }} </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">{{ trans('communication.table.message.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
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
                'name'  => $recipient->first_name . ' ' . $recipient->last_name,
                'type'  => 'an email'
            )) }}</p>
            <form action="{{ action('EmailController@add') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="recipient" value="{{ $recipient->id }}"/>
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

    @foreach($emails as $email)
    <div class="ui tiny modal" id="readEmail{{ $email->id }}Modal">
        <div class="ui icon header">
            <i class="envelope open outline icon"></i>
            {{ $email->subject }}
        </div>
        <div class="scrolling content">
            <p>{{ $email->message }}</p>
            <p>
                {{ trans('communication.modal.content.yours') }},<br/>
                {{ $email->sender->first_name . ' ' . $email->sender->last_name }}
            </p>
        </div>
        <div class="actions">
            <div class="ui red cancel button">
                <i class="remove icon"></i>
                {{ trans('communication.modal.button.dismiss') }}
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('page_scripts')
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

                $insura.helpers.initModal('div.modal', true);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form button.delete', {
                    confirmButtonText: '{{ trans('communication.swal.warning.delete.confirm') }}',
                    text: '{{ trans('communication.swal.warning.delete.text', array(
                        'type'  => 'email'
                    )) }}',
                    title: '{{ trans('communication.swal.warning.delete.title') }}'
                });
                $insura.helpers.listenForChats();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
