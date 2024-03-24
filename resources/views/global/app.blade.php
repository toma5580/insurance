<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Insura is an online Insurance Agency Management System" name="description">
    <meta content="Simcy Creative" name="author">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>@yield('title') - {{ config('insura.name') }} | Insurance Agency Management System</title>
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
    @yield('page_stylesheets')
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!-- Extra Customization CSS -->
    @yield('extra_stylesheets')
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

    @section('content')
    <div class="page-content">
        @yield('profile_bar')
        <div class="page-title with-desc">
            @yield('action_buttons')
            <h2>@yield('title')</h2>
            <p>@yield('sub_title')</p>
        </div>
        @show
    </div>

    <!-- Start Page Modals -->
    @yield('modals')
    <!-- End Page Modals -->

    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/semantic-UI/semantic.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/insura.js') }}" type="text/javascript"></script>
    <!-- Page Specific Scripts -->
    <script src="{{ asset('assets/libs/scrollbars/jquery.scrollbar.min.js') }}" type="text/javascript"></script>
    @yield('page_scripts')
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <!-- Extra Scripts -->
    @yield('extra_scripts')

</body>
</html>