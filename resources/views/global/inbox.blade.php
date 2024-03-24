<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Insura is an online Insurance Agency Management System" name="description">
    <meta content="Simcy Creative" name="author">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ trans('inbox.title') }} - {{ config('insura.name') }} | Insurance Agency Management System</title>
    <base href="{{ env('BASE_HREF', '') }}" />
    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon" sizes="16x16" type="image/x-icon">
    <link href="{{ asset('uploads/images/' . config('insura.favicon')) }}" rel="icon" type="{{ mime_content_type(storage_path() . '/app/images/' . config('insura.favicon')) }}">
    <!-- Font and Icon Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700" rel="stylesheet">
    <link href="{{ asset('assets/fonts/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <!-- Core CSS -->
    <link href="{{ asset('assets/libs/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/semantic-UI/semantic.min.css') }}" rel="stylesheet">
    <!-- Page Specific CSS -->
    <link href="{{ asset('assets/libs/scrollbars/jquery.scrollbar.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/chat.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    @include('global.header')
    
    @include($user->role . '.sidebar')

    <!-- Start message list -->
    <div class="inbox-left">
        <div class="top">
            <div class="ui fluid selection search dropdown">
                <input type="hidden" id="contact" value="X"/>
                <i class="dropdown icon"></i>
                <div class="default text"> <i class="chat icon"></i> {{ trans('inbox.input.label.new_chat') }} </div>
                <div class="menu">
                    <div class="item active disabled selected" data-value="X">
                        <i class="chat icon"></i> {{ trans('inbox.input.placeholder.new_chat') }}
                    </div>
                    @forelse($contacts->keyBy('id')->except($chatees->all()) as $contact)
                    <div class="item" data-value="{{ $contact->id }}">
                        <img class="ui mini avatar image" src="{{ asset('uploads/images/users/' . $contact->profile_image_filename) }}">
                        {{ $contact->fullName }}
                    </div>
                    @empty
                    <div class="item disabled" data-value="">
                        <i class="ion-alert-circled icon"></i> {{ trans('inbox.input.option.empty.new_chat') }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="people-list">
            <ul class="people">
                <li class="person" data-person="X">
                    <i class="chat icon"></i>
                    <span class="name">New Chat</span>
                    <span class="preview text-ellipsis">{{ trans('inbox.bubble.new') }}</span>
                </li>
                @foreach ($contacts as $contact)
                <li class="person" data-person="{{ $contact->id }}"{!! in_array($contact->id, $chatees->all()) ? '' : ' style="display:none;"' !!}>
                    <img src="{{ asset('uploads/images/users/' . $contact->profile_image_filename) }}" alt="{{ $contact->first_name }}'s Profile Photo" />
                    <span class="name">
                        {{ $contact->fullName }} 
                        @if ($contact->unreadChats->count() > 0)
                        <span class="ui tiny green circular label">{{ $contact->unreadChats->count() }}</span>
                        @endif
                    </span>
                    <span class="time">{{ $contact->chats->count() > 0 ? $contact->chats->last()->peopleListTime : '' }}</span>
                    <span class="preview text-ellipsis" data-message="{{ $contact->chats->last()->id or '' }}">{{ $contact->chats->last()->message or '' }}</span>
                    <span class="status" data-message="{{ $contact->chats->last()->id or '' }}">
                        @if ($contact->chats->count() > 0 && $contact->chats->last()->class === 'me')
                        <i class="ion-android-done{{ array(
                            'received'  => '-all',
                            'seen'      => '-all message-seen',
                            'sent'      => ''
                        )[$contact->chats->last()->status] }} icon"></i>
                        @endif
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- End message list -->

    <!-- Start inbox view -->
    <div class="inbox-right">
        <div class="top">
        <button class="ui left floated mini button inbox-back"> {{ trans('inbox.button.back') }} </button>
        <span>{{ trans('inbox.label.top.to') }}: <span class="name"> {{ trans('inbox.label.top.you') }} </span></span>
        </div>
        <div class="chat active-chat" data-chat="X">
            <div class="conversation-start">
                <span> {{ trans('inbox.label.start') }} </span>
            </div>
            <div class="bubble you">
                {{ trans('inbox.bubble.new') }}
                <span>{{ date('Y-m-d H:i:s') }}</span>
            </div>
        </div>
        @foreach ($contacts as $contact)
        <div class="chat" data-chat="{{ $contact->id }}">
            @foreach ($contact->chats as $key => $chat)
            @if ($key === 0 || $contact->chats->all()[$key - 1]->inboxTime != $chat->inboxTime)
            <div class="conversation-start">
                <span>{{ $chat->inboxTime }}</span>
            </div>
            @endif
            <div class="bubble {{ $chat->class }}" data-message="{{ $chat->id }}">
                {{ $chat->message }}
                @if ($chat->class === 'me')
                <i class="ion-android-done{{ array(
                    'received'  => '-all grey',
                    'seen'      => '-all blue',
                    'sent'      => ' grey'
                )[$chat->status] }} icon"></i>
                @endif
                <span>{{ $chat->created_at }}</span>
            </div>
            @endforeach
            @if ($contact->chats->count() === 0 || $contact->chats->last()->inboxTime !== trans('inbox.label.time.today'))
            <div class="conversation-start">
                <span>{{ trans('inbox.label.time.today') }}</span>
            </div>
            @endif
        </div>
        @endforeach
        <form action="{{ action('ChatController@send') }}" class="write">
            <input type="hidden" name="recipient" value=""/>
            <input type="text" disabled name="message" placeholder="{{ trans('inbox.input.placeholder.message') }}" required/>
            <button class="circular right floated ui icon button transparent" type="submit"> <i class="icon send"></i></button>
        </form>
    </div>
    <!-- End inbox view -->

    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script> 
    <script src="{{ asset('assets/libs/semantic-UI/semantic.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/insura.js') }}" type="text/javascript"></script>
    <!-- Page Specific Scripts -->
    <script src="{{ asset('assets/libs/scrollbars/jquery.scrollbar.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $insura.vars = {
                lastChatee: '{{ $chatees ->last() }}'
            };
            $(document).ready(function() {
                $insura.helpers.initDropdown('div.dropdown');
                $insura.helpers.initScrollbar('div.scrollbar');
            });
        })(window.insura, window.jQuery);
    </script>
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/inbox.js') }}" type="text/javascript"></script>
</body>
</html>