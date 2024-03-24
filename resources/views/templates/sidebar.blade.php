<aside class="aside-menu">
        <div class="branding">
            <div class="sm-show close-aside">
                <i class="ion-ios-close-outline"></i>
            </div>
            <a href="">
                <img src="{{ asset('uploads/images/' . config('insura.logo')) }}" class="ui image">
            </a>
        </div>
        <div class="scrollbar">
            <div class="ui link list">
                <a class="item" href="{{ action('IndexController@getDashboard') }}">
                    <i class="ion-ios-speedometer-outline icon"></i> <div class="content">{{ trans('sidebar.link.dashboard') }} </div>
                </a>

                @yield('clients')

                <a class="item" href="{{ action('PolicyController@getAll') }}">
                    <i class="ion-ios-bookmarks-outline icon"></i> <div class="content">{{ trans('sidebar.link.policies') }} </div>
                </a>

                @yield('brokers')

                <a class="item insura-chats" href="{{ action('InboxController@getAll') }}">
                    @if ($unread_chats_count > 0)
                    <span class="ui red circular label pull-right">{{ $unread_chats_count }}</span>
                    @endif
                    <i class="ion-ios-chatbubble-outline icon"></i>
                    <div class="content">{{ trans('sidebar.link.inbox') }}</div>
                </a>
                <a class="item" href="{{ action('CommunicationController@get') }}">
                    <i class="ion-ios-chatboxes-outline icon"></i> <div class="content">{{ trans('sidebar.link.communication') }} </div>
                </a>
                <a class="item" href="{{ action('ReportController@get') }}">
                    <i class="ion-ios-pulse-strong icon"></i> <div class="content">{{ trans('sidebar.link.reports') }} </div>
                </a>

                @yield('products')

                @yield('companies')

                @yield('staff')
                
                <a class="item" href="{{ action('SettingController@get') }}">
                    <i class="ion-ios-gear-outline icon"></i> <div class="content">{{ trans('sidebar.link.settings') }} </div>
                </a>
            </div>
        </div>
    </aside>