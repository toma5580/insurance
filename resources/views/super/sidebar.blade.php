@extends('templates.sidebar')

@section('clients')
            <a class="item" href="{{ action('ClientController@getAll') }}">
                <i class="ion-ios-people-outline icon"></i> <div class="content">{{ trans('sidebar.link.clients') }} </div>
            </a>
@endsection

@section('brokers')
            <a class="item" href="{{ action('BrokerController@getAll') }}">
                <i class="ion-ios-briefcase-outline icon"></i> <div class="content">{{ trans('sidebar.link.brokers') }} </div>
            </a>
@endsection

@section('products')
            <a class="item" href="{{ action('ProductController@getAll') }}">
                <i class="ion-ios-lightbulb-outline icon"></i> <div class="content">{{ trans('sidebar.link.products') }} </div>
            </a>
@endsection

@section('companies')
            <a class="item" href="{{ action('CompanyController@getAll') }}">
                <i class="ion-ios-flower-outline icon"></i> <div class="content">{{ trans('sidebar.link.companies') }} </div>
            </a>
@endsection

@section('staff')
            <a class="item" href="{{ action('StaffController@getAll') }}">
                <i class="ion-ios-person-outline icon"></i> <div class="content">{{ trans('sidebar.link.staff') }} </div>
            </a>
@endsection
