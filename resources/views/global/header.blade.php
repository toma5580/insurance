<header>
        <!-- top right section -->
        <div class="ui right floated segment header-links">
            <div class="ui left floated segment header-icon-links">
                <!-- icon links -->
                <div class="ui very relaxed horizontal list">
                    <div class="item">
                        <div class="bottom aligned content">
                            <a class="header insura-chats" data-inverted="" data-position="bottom center" data-tooltip="{{ trans('header.tooltip.message') }}" href="{{ action('InboxController@getAll') }}">
                                @if ($unread_chats_count > 0)
                                <span class="ui red circular floating label">{{ $unread_chats_count }}</span>
                                @endif
                                <i class="ion-ios-chatbubble-outline"></i>
                            </a>
                        </div>
                    </div>
                    {{-- <div class="item">
                        <div class="bottom aligned content">
                            <a class="header insura-notifications" data-inverted="" data-position="bottom center" data-tooltip="{{ trans('header.tooltip.notification') }}" href="">
                                <i class="ion-ios-bell-outline"></i>
                            </a>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- profile -->
            <div class="ui left floated segment header-avatar">
                <div class="ui very relaxed horizontal list">
                    <div class="ui item top-avatar top right pointing icon dropdown">
                        <img class="ui avatar image" src="{{ asset('uploads/images/users/' . $user->profile_image_filename) }}">
                        <div class="middle aligned content">
                            <a class="header">{{ $user->first_name }} <i class="ion-ios-arrow-down"></i></a>
                        </div>

                        <div class="menu">
                            <div class="header">
                                {{ trans('header.header.profile') }}
                            </div>
                            <div class="divider"></div>
                            <div class="item">
                                <a href="{{ action('SettingController@get') . '#profile' }}"><i class="ion-person icon"></i> {{ trans('header.link.profile') }}</a>
                            </div>
                            <div class="item">
                                <a href="{{ action('SettingController@get') }}"><i class="ion-gear-a icon"></i> {{ trans('header.link.settings') }}</a>
                            </div>
                            <div class="item active selected">
                                <a href="{{ action('Auth\AuthController@getLogout') }}"><i class="ion-log-out icon"></i> {{ trans('header.link.log_out') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- search -->
        <div class="header-search">
            <div class="ui category search">
                <div class="ui icon input">
                    <input class="prompt" type="text" placeholder="{{ trans('header.input.placeholder.search') }}">
                    <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
        </div> --}}

        <!-- branding & logo -->
        <div class="small-monitor">
            <div class="humbager">
                <a href="javascript:void(0);">
                    <i class="ion-navicon"></i>
                </a>
            </div>
            <div class="branding">
                <a href="{{ action('IndexController@getDashboard') }}">
                    <img src="{{ asset('uploads/images/' . config('insura.logo')) }}" class="ui image">
                </a>
            </div>
            <div class="branding-icon">
                <a href="{{ action('IndexController@getDashboard') }}">
                    <img src="{{ asset('uploads/images/' . config('insura.favicon')) }}" class="ui image">
                </a>
            </div>
        </div>
    </header>